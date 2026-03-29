<?php
$codigo = "";
$logs = "";
$js = "";
$tabla_simbolos_html = "";

if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    file_put_contents("temp.txt", $codigo);
    
    // Ejecutamos el compilador
    $salida = shell_exec("compilador_final.exe < temp.txt 2>&1");
    
    // Procesamos la salida línea por línea
    if ($salida) {
        $lineas = explode("\n", trim($salida));
        foreach ($lineas as $linea) {
            if (strpos($linea, "[LOG]") === 0) {
                $logs .= htmlspecialchars(str_replace("[LOG] ", "", $linea)) . "\n";
            } elseif (strpos($linea, "[JS]") === 0) {
                $js .= htmlspecialchars(str_replace("[JS] ", "", $linea)) . "\n";
            } elseif (strpos($linea, "[SYM]") === 0) {
                // Separamos "Tipo | Nombre" para hacer la tabla HTML
                $datos = explode("|", str_replace("[SYM] ", "", $linea));
                if (count($datos) == 2) {
                    $tabla_simbolos_html .= "<tr><td>" . htmlspecialchars(trim($datos[0])) . "</td><td>" . htmlspecialchars(trim($datos[1])) . "</td><td>Dinámica (Let)</td></tr>";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compilador Unificado - Dashboard</title>
    <style>
        :root {
            --bg: #0f172a;
            --panel: #1e293b;
            --border: #334155;
            --accent: #38bdf8;
            --java: #f87171;
            --js: #facc15;
            --success: #4ade80;
        }
        body { background: var(--bg); color: #f1f5f9; font-family: 'Segoe UI', Consolas, sans-serif; margin: 0; padding: 20px; }
        header { text-align: center; margin-bottom: 20px; }
        h1 { color: var(--accent); letter-spacing: 2px; margin: 0; }
        
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .panel {
            background: var(--panel);
            border: 2px solid var(--border);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .panel-header {
            background: rgba(0,0,0,0.2);
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 2px solid var(--border);
        }

        textarea, .content {
            flex-grow: 1;
            height: 250px;
            background: transparent;
            color: #e2e8f0;
            border: none;
            padding: 15px;
            font-family: Consolas, monospace;
            font-size: 15px;
            resize: none;
            outline: none;
            overflow-y: auto;
        }

        textarea { color: #f472b6; }
        .logs-content { color: var(--success); }
        .js-content { color: var(--js); }

        /* Estilos específicos para la Tabla de Símbolos */
        table { width: 100%; border-collapse: collapse; font-family: Consolas, monospace; }
        th { background: rgba(56, 189, 248, 0.1); color: var(--accent); padding: 10px; text-align: left; border-bottom: 1px solid var(--border); }
        td { padding: 10px; border-bottom: 1px solid #334155; }
        
        button {
            grid-column: span 2;
            padding: 15px;
            background: var(--accent);
            color: var(--bg);
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }
        button:hover { filter: brightness(1.2); }
    </style>
</head>
<body>

<header>
    <h1>D A S H B O A R D _ C O M P I L A D O R</h1>
    <p style="color: #94a3b8; margin-top: 5px;">Análisis Léxico, Sintáctico, Semántico y Generación de Código</p>
</header>

<form class="dashboard" method="POST">
    
    <div class="panel">
        <div class="panel-header" style="color: var(--java);">1. Código Fuente (Origen)</div>
        <textarea name="codigo" spellcheck="false" placeholder="Escribe aquí..."><?= htmlspecialchars($codigo) ?></textarea>
    </div>

    <div class="panel">
        <div class="panel-header" style="color: var(--js);">4. Código Generado (Destino JS)</div>
        <div class="content js-content"><pre style="margin:0;"><?= $js ?></pre></div>
    </div>

    <div class="panel">
        <div class="panel-header" style="color: var(--success);">2. Consola de Análisis (Léxico/Sintáctico/Semántico)</div>
        <div class="content logs-content"><pre style="margin:0;"><?= $logs ?></pre></div>
    </div>

    <div class="panel">
        <div class="panel-header" style="color: var(--accent);">3. Tabla de Símbolos (Memoria)</div>
        <div class="content" style="padding: 0;">
            <table>
                <thead>
                    <tr><th>Tipo de Dato</th><th>Identificador (ID)</th><th>Asignación en Destino</th></tr>
                </thead>
                <tbody>
                    <?= $tabla_simbolos_html ?>
                </tbody>
            </table>
        </div>
    </div>

    <button type="submit">E J E C U T A R _ C O M P I L A C I Ó N</button>
</form>

</body>
</html>