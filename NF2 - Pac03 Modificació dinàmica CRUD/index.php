<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió de Taules</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h1>Gestió de Taules</h1>
    <div class="tables-list">
        <h2>Llista de Taules</h2>
        <ul id="tablesList"></ul>
    </div>
    <div class="form-container">
        <form id="intelForm">
            <input type="text" id="commandInput" placeholder="Enter command">
            <button type="submit">Enviar</button>
        </form>
        <div id="resultContainer"></div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./scripts.js"></script>
</body>
</html>
