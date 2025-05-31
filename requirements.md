# API Requirements

## RFs (Requisitos Funcionais)

### Autenticação

-   [x] Deve ser possível criar uma conta com nome, email, celular e senha.
-   [x] Deve ser possível fazer login com email e senha.
-   [x] Deve ser possível fazer logout.
-   [x] Deve ser possível recuperar a senha.

### User

-   [x] Deve ser possível editar o nome do usuário.
-   [x] Deve ser possível editar o email do usuário.
-   [x] Deve ser possível editar o celular do usuário.
-   [x] Deve ser possível editar a senha do usuário.
-   [x] Deve ser possível deletar a conta do usuário.

### Business

-   [x] Deve ser possível criar um negócio com nome, descrição, telefone, email e endereço.
-   [x] Deve ser possível editar um negócio.
-   [x] Deve ser possível deletar um negócio.
-   [x] Deve ser possível listar todos os negócios de um usuário.
-   [x] Deve ser possível listar um negócio específico.

### Review

-   [x] Deve ser possível criar uma avaliação com nome, nota e comentário.
-   [x] Deve ser possível listar todas as avaliações de um negócio.
-   [x] Usuário dono do negócio poderá responder a uma avaliação.

### Planos

-   [x] Deve ser possível assinar um plano entre basic e pro.
-   [x] Deve ser possível cancelar a assinatura.
-   [x] Deve ser possível ver o plano atual.
-   [x] Deve ser possível ver o histórico de assinaturas.
-   [x] Deve ser possível ver o histórico de pagamentos.

### Notification

-   [x] Deve ser possível enviar uma notificação para um usuário.
-   [x] Deve ser possível listar todas as notificações de um usuário.
-   [x] Deve ser possível marcar uma notificação como lida.
-   [x] Deve ser possível deletar uma notificação.
-   [x] Deve ser possível enviar uma notificação para um usuário via whatsapp.

## RN (Regras de Negócio)

-   [x] Usuário no plano **basic** pode criar 1 negócio.
-   [x] Usuário no plano **pro** pode criar até 3 negócios.
-   [x] Usuário no plano **basic** não poderá moderar as avaliações.
-   [x] Usuário no plano **pro** poderá moderar as avaliações.
-   [x] Usuário no plano **basic** não poderá responder as avaliações.
-   [x] Usuário no plano **pro** poderá responder as avaliações.
-   [x] Usuário no plano **basic** não poderá enviar notificações para um usuário nem via app, nem via whatsapp.
-   [x] Usuário no plano **pro** poderá enviar notificações para um usuário via app, e via whatsapp.
