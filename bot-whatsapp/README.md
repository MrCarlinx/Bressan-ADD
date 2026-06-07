# Toca do Coelho - Bot do WhatsApp 🐰

Este é um chatbot automatizado desenvolvido em Node.js com a biblioteca `whatsapp-web.js`. Ele foi criado baseado nos fluxos de atendimento de sucesso analisados.

O bot tem a funcionalidade de interceptar as mensagens pré-programadas do site (como o link de contato do cabeçalho e, principalmente, a mensagem gerada ao finalizar uma compra no carrinho) para já oferecer opções automatizadas aos seus clientes e poupar o tempo dos seus atendentes humanos.

## Funcionalidades
1. **Atendimento de Novos Pedidos (Checkout):** O bot percebe que a mensagem contém "Gostaria de finalizar meu pedido (#ID)" e já entrega um menu específico de opções de pagamento (PIX com 5% desconto, Pagar na Entrega ou Retirar na Loja).
2. **Atendimento Geral:** Se a pessoa vem pelo link padrão do site ("Olá! Vim pelo site..."), o bot entrega o menu geral de dúvidas e horários.
3. **Transferência para Humano:** O bot transfere (encerra as respostas automáticas) para o atendente caso o cliente escolha a opção e interaja. 

## Como Instalar e Rodar

Para rodar este bot, você precisará do **Node.js** instalado na sua máquina.

1. Abra o Terminal e acesse esta pasta:
   ```bash
   cd c:\xampp\htdocs\toca_do_coelho\bot-whatsapp
   ```

2. Instale as dependências:
   ```bash
   npm install
   ```

3. Inicie o Bot:
   ```bash
   npm start
   ```

4. **Autenticação:** Quando você rodar o comando acima, um QR Code enorme vai aparecer no seu terminal. Pegue o celular do WhatsApp Comercial da loja, vá em "Aparelhos Conectados" e escaneie o QR Code, assim como se faz com o WhatsApp Web.

5. **Pronto!** O bot estará online enquanto o terminal estiver aberto! Pode testar mandando uma mensagem de outro celular para o número da loja.
