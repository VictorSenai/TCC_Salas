<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
<style>
        /* Reset e configurações básicas */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        line-height: 1.6;
    }

    /* Título principal */
    .title {
        color: white;
        font-size: 2.8rem;
        text-align: center;
        margin-bottom: 50px;
        font-weight: 700;
        text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        position: relative;
        padding-bottom: 15px;
    }

    .title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
        border-radius: 2px;
    }

    /* Seção dos botões */
    section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        max-width: 1200px;
        width: 100%;
        padding: 30px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
    }

    /* Estilos dos botões */
    .button {
        padding: 18px 25px;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        min-height: 70px;
        text-align: center;
        box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
    }

    /* Efeito de brilho ao passar o mouse */
    .button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s;
    }

    .button:hover::before {
        left: 100%;
    }

    .button:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #764ba2, #667eea);
    }

    .button:active {
        transform: translateY(-2px);
    }

    /* Cores específicas para diferentes tipos de botões */
    .button:nth-child(1) { /* Adicionar Sala */
        background: linear-gradient(135deg, #4CAF50, #45a049);
        box-shadow: 0 6px 15px rgba(76, 175, 80, 0.3);
    }

    .button:nth-child(2) { /* Listar Salas */
        background: linear-gradient(135deg, #2196F3, #0b7dda);
        box-shadow: 0 6px 15px rgba(33, 150, 243, 0.3);
    }

    .button:nth-child(3) { /* Suportes Feitos */
        background: linear-gradient(135deg, #ff9800, #e68900);
        box-shadow: 0 6px 15px rgba(255, 152, 0, 0.3);
    }

    .button:nth-child(4) { /* Ver Pedidos de Suporte */
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        box-shadow: 0 6px 15px rgba(156, 39, 176, 0.3);
    }

    .button:nth-child(5) { /* Adicionar Professores */
        background: linear-gradient(135deg, #009688, #00796b);
        box-shadow: 0 6px 15px rgba(0, 150, 136, 0.3);
    }

    .button:nth-child(6) { /* Listar Professores */
        background: linear-gradient(135deg, #607d8b, #455a64);
        box-shadow: 0 6px 15px rgba(96, 125, 139, 0.3);
    }

    .button:nth-child(7) { /* Sair */
        background: linear-gradient(135deg, #f44336, #d32f2f);
        box-shadow: 0 6px 15px rgba(244, 67, 54, 0.3);
    }

    .button:nth-child(8) { /* Comunicar Suporte */
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        box-shadow: 0 6px 15px rgba(255, 107, 107, 0.3);
    }

    /* Efeitos hover específicos por cor */
    .button:nth-child(1):hover { background: linear-gradient(135deg, #45a049, #4CAF50); }
    .button:nth-child(2):hover { background: linear-gradient(135deg, #0b7dda, #2196F3); }
    .button:nth-child(3):hover { background: linear-gradient(135deg, #e68900, #ff9800); }
    .button:nth-child(4):hover { background: linear-gradient(135deg, #7b1fa2, #9c27b0); }
    .button:nth-child(5):hover { background: linear-gradient(135deg, #00796b, #009688); }
    .button:nth-child(6):hover { background: linear-gradient(135deg, #455a64, #607d8b); }
    .button:nth-child(7):hover { background: linear-gradient(135deg, #d32f2f, #f44336); }
    .button:nth-child(8):hover { background: linear-gradient(135deg, #ee5a52, #ff6b6b); }

    /* Ícones (se quiser adicionar posteriormente) */
    .button i {
        font-size: 1.3rem;
    }

    /* Efeitos de foco para acessibilidade */
    .button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5), 0 0 0 6px rgba(102, 126, 234, 0.3);
    }

    /* Responsividade */
    @media (max-width: 768px) {
        body {
            padding: 20px 15px;
        }
        
        .title {
            font-size: 2.2rem;
            margin-bottom: 30px;
        }
        
        section {
            grid-template-columns: 1fr;
            gap: 15px;
            padding: 20px;
        }
        
        .button {
            padding: 16px 20px;
            font-size: 1rem;
            min-height: 60px;
        }
    }

    @media (max-width: 480px) {
        .title {
            font-size: 1.8rem;
        }
        
        section {
            padding: 15px;
        }
        
        .button {
            padding: 14px 18px;
            font-size: 0.95rem;
            min-height: 55px;
        }
    }

    /* Animação de entrada suave */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    section {
        animation: fadeInUp 0.8s ease-out;
    }

    .button {
        animation: fadeInUp 0.6s ease-out;
    }

    .button:nth-child(1) { animation-delay: 0.1s; }
    .button:nth-child(2) { animation-delay: 0.2s; }
    .button:nth-child(3) { animation-delay: 0.3s; }
    .button:nth-child(4) { animation-delay: 0.4s; }
    .button:nth-child(5) { animation-delay: 0.5s; }
    .button:nth-child(6) { animation-delay: 0.6s; }
    .button:nth-child(7) { animation-delay: 0.7s; }
    .button:nth-child(8) { animation-delay: 0.8s; }

    /* Estado de loading (opcional para futuras implementações) */
    .button.loading {
        pointer-events: none;
        opacity: 0.8;
    }

    .button.loading::after {
        content: '';
        width: 18px;
        height: 18px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


    <h1 id="title" class="title">Bem Vindo ao Sistema de Salas</h1>
<section>
    <button onclick="window.location.href='processar_adicao_sala.php'" class="button">Adicionar Sala</button>
    <button onclick="window.location.href='lista_sala.php'" class="button">Listar Salas</button>
    <button onclick="window.location.href='reservar_sala.php'" class="button">Suportes Feitos</button>
    <button onclick="window.location.href='visualizar_pedido_sup.php'" class="button">Ver Pedidos de Suporte</button>
    <button onclick="window.location.href='cadastro_prof.php'" class="button">Adicionar Professores</button>
    <button onclick="window.location.href='lista_professores.php'" class="button">Listar Professores</button>
    <button onclick="window.location.href='listar_professores.php'" class="button">Sair</button>
<button onclick="window.location.href='suporte.php'" class="button">Comunicar Suporte</button>

</section>
</body>
</html>

