<?php

$horaAtual = true;
$paginaAtiva = 'home';
include '../src/includes/session.php'; // sessão primeiro
include '../src/includes/header_adm.php'; // só depois do redirect seguro

echo '<title>Home - AutoPass</title>';

?>
        <!-- CONTENT -->
        <div class="content">


   

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<?php
include '../src/includes/footer.php';
?>