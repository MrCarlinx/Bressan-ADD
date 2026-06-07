const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const fs = require('fs');
const path = require('path');

// ============================================================
// ⚙️  CONFIGURAÇÕES CENTRALIZADAS
// ============================================================
// Edite apenas este bloco para personalizar o bot!
const CONFIG = {
    // Dados do PIX
    PIX_CHAVE:          '00.000.000/0001-00',         // ⚠️ Substitua pelo CNPJ real
    PIX_NOME:           'Toca do Coelho Utilidades',  // ⚠️ Substitua pelo nome real

    // Informações da loja
    LOJA_ENDERECO:      'Rua Sete de Setembro, 1234 - Centro, Porto Velho - RO',
    LOJA_HORARIO:       'Segunda a Sexta: 08h às 18h\nSábado: 08h às 14h',

    // Sessões
    SESSION_TIMEOUT_MS:  30 * 60 * 1000,  // 30 min de inatividade reseta a sessão
    SESSION_CLEANUP_MS:  10 * 60 * 1000,  // Limpeza de sessões a cada 10 min
    SESSIONS_FILE:       path.join(__dirname, 'sessions.json'),

    // Rate limiting
    RATE_LIMIT_MAX:      10,               // Máx. mensagens por janela
    RATE_LIMIT_WINDOW_MS: 60 * 1000,      // Janela de 1 minuto
};

// ============================================================
// 📋 LOGGER COM TIMESTAMP
// ============================================================
const log = {
    info:  (msg, data) => _log('INFO ', msg, data),
    warn:  (msg, data) => _log('WARN ', msg, data),
    error: (msg, data) => _log('ERROR', msg, data),
};

function _log(level, msg, data) {
    const ts = new Date().toLocaleString('pt-BR', { hour12: false });
    const base = `[${ts}] [${level}] ${msg}`;
    if (data !== undefined) console.log(base, typeof data === 'object' ? JSON.stringify(data) : data);
    else console.log(base);
}

// ============================================================
// 💾 PERSISTÊNCIA DE SESSÕES (arquivo JSON)
// ============================================================
function loadSessions() {
    try {
        if (fs.existsSync(CONFIG.SESSIONS_FILE)) {
            const raw = fs.readFileSync(CONFIG.SESSIONS_FILE, 'utf-8');
            const parsed = JSON.parse(raw);
            log.info(`Sessões carregadas: ${Object.keys(parsed).length} ativas.`);
            return new Map(Object.entries(parsed));
        }
    } catch (e) {
        log.warn('Falha ao carregar sessions.json. Iniciando com estado vazio.', e.message);
    }
    return new Map();
}

function saveSessions(sessions) {
    try {
        const obj = {};
        sessions.forEach((val, key) => { obj[key] = val; });
        fs.writeFileSync(CONFIG.SESSIONS_FILE, JSON.stringify(obj, null, 2));
    } catch (e) {
        log.error('Falha ao salvar sessions.json.', e.message);
    }
}

// ============================================================
// 🚦 RATE LIMITING (por número)
// ============================================================
const rateLimiter = new Map(); // numero → { count, windowStart }

function isRateLimited(from) {
    const now = Date.now();
    const entry = rateLimiter.get(from) || { count: 0, windowStart: now };

    // Janela expirada → reseta
    if (now - entry.windowStart > CONFIG.RATE_LIMIT_WINDOW_MS) {
        rateLimiter.set(from, { count: 1, windowStart: now });
        return false;
    }

    if (entry.count >= CONFIG.RATE_LIMIT_MAX) return true;

    entry.count++;
    rateLimiter.set(from, entry);
    return false;
}

// ============================================================
// 🗂️ GESTÃO DE SESSÕES
// ============================================================
const sessions = loadSessions();

function getSession(from) {
    if (!sessions.has(from)) {
        sessions.set(from, { etapa: 'inicio', pedidoId: null, ultimaAtividade: Date.now() });
    }

    const sessao = sessions.get(from);

    // Verificar timeout de inatividade
    if (sessao.etapa !== 'inicio' && sessao.etapa !== 'atendimento_humano') {
        const inativo = Date.now() - (sessao.ultimaAtividade || 0);
        if (inativo > CONFIG.SESSION_TIMEOUT_MS) {
            log.info(`Sessão de ${from} expirada por inatividade. Resetando.`);
            sessao.etapa = 'inicio';
            sessao.pedidoId = null;
        }
    }

    sessao.ultimaAtividade = Date.now();
    return sessao;
}

// Limpeza periódica de sessões muito antigas (2x o timeout)
setInterval(() => {
    const now = Date.now();
    let removed = 0;
    sessions.forEach((sessao, from) => {
        if ((now - (sessao.ultimaAtividade || 0)) > CONFIG.SESSION_TIMEOUT_MS * 2) {
            sessions.delete(from);
            removed++;
        }
    });
    if (removed > 0) {
        log.info(`Limpeza: ${removed} sessão(ões) expirada(s) removida(s).`);
        saveSessions(sessions);
    }
}, CONFIG.SESSION_CLEANUP_MS);

// ============================================================
// 🔤 NORMALIZAÇÃO DE INPUT
// ============================================================
function normalizeInput(body) {
    // Remove espaços, zeros à esquerda, pontos finais
    // Exemplos: " 1 " → "1", "01" → "1", "1." → "1"
    return body.trim().replace(/^0+(\d)/, '$1').replace(/\.$/, '');
}

// ============================================================
// 💬 TEMPLATES DE MENSAGEM
// ============================================================
const MSG = {
    menuGeral: () =>
        `🐰 *Olá! A Toca do Coelho agradece seu contato.*\n\n` +
        `Como podemos te ajudar hoje?\n` +
        `*1️⃣* - Dúvidas sobre produtos\n` +
        `*2️⃣* - Acompanhar meu pedido\n` +
        `*3️⃣* - Endereço e Horários de Funcionamento\n` +
        `*4️⃣* - Falar com Atendente\n\n` +
        `_Digite o número da opção desejada:_`,

    menuPedido: (pedidoId) =>
        `🐰 *Olá! Toca do Coelho agradece a preferência!*\n\n` +
        `Identificamos o seu pedido *#${pedidoId || 'Desconhecido'}* em nosso sistema.\n\n` +
        `Como você prefere realizar o pagamento e entrega?\n` +
        `*1️⃣* - Pagar agora com PIX (5% de Desconto)\n` +
        `*2️⃣* - Pagar na Entrega (Cartão/Dinheiro)\n` +
        `*3️⃣* - Retirar e Pagar na Loja\n` +
        `*4️⃣* - Falar com Atendente\n\n` +
        `_Digite o número da opção, ou *0* para o menu principal:_`,

    erroPadrao: () =>
        `⚠️ Desculpe, não entendi sua mensagem.\n\n` +
        `Por favor, responda com um dos números listados no menu.\n` +
        `_Ou digite *0* a qualquer momento para voltar ao menu principal._`,

    voltouMenu: () =>
        `↩️ Ok! Voltando ao menu principal.\n\n`,
};

// ============================================================
// 📱 CLIENTE WHATSAPP
// ============================================================
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--no-first-run',
            '--disable-gpu',
        ],
    },
});

client.on('qr', (qr) => {
    log.info('📱 Escaneie o QR Code abaixo com seu WhatsApp:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    log.info('✅ Bot da Toca do Coelho conectado e pronto!');
});

// Reconexão automática
client.on('disconnected', (reason) => {
    log.warn(`Bot desconectado (${reason}). Tentando reconectar em 5s...`);
    setTimeout(() => {
        client.initialize().catch((e) => log.error('Falha na reconexão.', e.message));
    }, 5000);
});

client.on('auth_failure', (msg) => {
    log.error(`Falha de autenticação: ${msg}`);
});

// ============================================================
// 📥 PROCESSAMENTO DE MENSAGENS
// ============================================================
client.on('message', async (msg) => {
    try {
        // Ignora grupos e mensagens não-texto
        if (msg.from.endsWith('@g.us') || msg.type !== 'chat') return;

        const from = msg.from;
        const body = normalizeInput(msg.body);

        log.info(`Msg de ${from}`, { etapa: sessions.get(from)?.etapa, body: body.substring(0, 60) });

        // Rate limiting
        if (isRateLimited(from)) {
            log.warn(`Rate limit atingido para ${from}. Mensagem ignorada.`);
            return;
        }

        const sessao = getSession(from);

        // Atendimento humano → bot fica silencioso
        if (sessao.etapa === 'atendimento_humano') return;

        // ── Comando global: voltar ao menu ────────────────────
        if (body === '0' || body.toLowerCase() === 'menu' || body.toLowerCase() === 'inicio') {
            sessao.etapa = 'inicio';
            saveSessions(sessions);
            await msg.reply(MSG.voltouMenu() + MSG.menuGeral());
            return;
        }

        // ── Mensagens iniciadas pelo site ─────────────────────

        // 1. Finalização de pedido (Checkout)
        if (body.startsWith('Olá! Gostaria de finalizar meu pedido (#')) {
            const match = body.match(/pedido\s*\(#(\d+)\)/i);
            const pedidoId = match ? match[1] : null;
            sessao.etapa = 'menu_pedido';
            sessao.pedidoId = pedidoId;
            saveSessions(sessions);
            await msg.reply(MSG.menuPedido(pedidoId));
            return;
        }

        // 2. CTA genérico do site (Header / botões)
        if (
            body.startsWith('Olá! Vim pelo site') ||
            body.startsWith('Olá! Gostaria de falar') ||
            body.startsWith('Olá! Quero transformar')
        ) {
            sessao.etapa = 'menu_geral';
            saveSessions(sessions);
            await msg.reply(MSG.menuGeral());
            return;
        }

        // ── Menu de Pedido ────────────────────────────────────
        if (sessao.etapa === 'menu_pedido') {
            switch (body) {
                case '1':
                    await msg.reply(
                        `📲 *Pagamento via PIX*\n\n` +
                        `Ótima escolha! Você ganha *5% de desconto*.\n\n` +
                        `*Chave PIX (CNPJ):* ${CONFIG.PIX_CHAVE}\n` +
                        `*Favorecido:* ${CONFIG.PIX_NOME}\n\n` +
                        `Assim que transferir, *envie o comprovante aqui nesta conversa*.\n` +
                        `_Um atendente irá confirmar em instantes!_ ✅`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                case '2':
                    await msg.reply(
                        `💳 *Pagamento na Entrega*\n\n` +
                        `Perfeito! Aceitamos cartão de débito/crédito e dinheiro.\n\n` +
                        `Por favor, informe seu *endereço completo com ponto de referência* para que nosso atendente calcule o frete.`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                case '3':
                    await msg.reply(
                        `🏪 *Retirada na Loja*\n\n` +
                        `Seu pedido já está sendo separado! 🎉\n\n` +
                        `📍 *Endereço:* ${CONFIG.LOJA_ENDERECO}\n\n` +
                        `🕒 *Horário:*\n${CONFIG.LOJA_HORARIO}\n\n` +
                        `Aguarde um momento enquanto um atendente confirma a disponibilidade dos itens.`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                case '4':
                    await msg.reply(
                        `👩‍💻 Perfeito! Estou transferindo seu atendimento para um de nossos atendentes.\n` +
                        `Por favor, aguarde alguns minutinhos. 🙏`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                default:
                    await msg.reply(MSG.erroPadrao());
                    break;
            }
            saveSessions(sessions);
            return;
        }

        // ── Menu Geral ────────────────────────────────────────
        if (sessao.etapa === 'menu_geral') {
            switch (body) {
                case '1':
                    await msg.reply(
                        `🛍️ *Dúvidas sobre Produtos*\n\n` +
                        `Sem problema! Pode digitar o nome do produto ou descrever o que está procurando.\n` +
                        `Um atendente irá te ajudar em instantes. 😊`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                case '2':
                    await msg.reply(
                        `📦 *Acompanhar Pedido*\n\n` +
                        `Para verificar o status, por favor informe:\n` +
                        `• O *Número do Pedido* (ex: #1234), ou\n` +
                        `• O *E-mail* cadastrado na sua conta.`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                case '3':
                    await msg.reply(
                        `📍 *Nossa Loja*\n` +
                        `${CONFIG.LOJA_ENDERECO}\n\n` +
                        `🕒 *Horário de Funcionamento:*\n` +
                        `${CONFIG.LOJA_HORARIO}\n\n` +
                        `Esperamos sua visita! 🐰\n\n` +
                        `_Digite *0* para voltar ao menu principal._`
                    );
                    sessao.etapa = 'inicio'; // Não precisa de atendente, pode voltar
                    break;

                case '4':
                    await msg.reply(
                        `👩‍💻 Certo! Estou transferindo você para nossa equipe de atendimento.\n` +
                        `Aguarde um instante, por favor. 🙏`
                    );
                    sessao.etapa = 'atendimento_humano';
                    break;

                default:
                    await msg.reply(MSG.erroPadrao());
                    break;
            }
            saveSessions(sessions);
            return;
        }

        // ── Estado inicial (qualquer mensagem sem contexto) ───
        if (sessao.etapa === 'inicio') {
            sessao.etapa = 'menu_geral';
            saveSessions(sessions);
            await msg.reply(MSG.menuGeral());
        }

    } catch (err) {
        log.error(`Erro ao processar mensagem de ${msg?.from}`, err.message);
        // Tenta notificar o usuário sem crashar o bot
        try {
            await msg.reply(`⚠️ Ocorreu um erro inesperado. Por favor, tente novamente ou aguarde um atendente.`);
        } catch (_) { /* silencia erro ao enviar o erro */ }
    }
});

// ============================================================
// 🚀 INICIALIZAÇÃO
// ============================================================
log.info('Iniciando bot Toca do Coelho...');
client.initialize();
