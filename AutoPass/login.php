<?php
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_GET['erro'])) {
    echo "<div style='position:fixed;top:20px;left:50%;transform:translateX(-50%);z-index:9999'
    class='alert alert-danger'>"
    . htmlspecialchars($_GET['erro']) .
    "</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login | AutoPass</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Inter,sans-serif;
}

#mainlogin{

min-height:100vh;
background:#f2f2f2;
overflow-x:hidden;
position:relative;

}

#mainlogin::before{

content:"";

position:fixed;

top:0;
left:0;

width:100%;
height:100%;

background:url("img/logo/fundo_login.png");

background-size:cover;
background-position:center;
background-repeat:no-repeat;

opacity:.3;

z-index:0;

pointer-events:none;
}

#mainlogin > *{

position:relative;
z-index:1;

}

.main-wrapper{

min-height:100vh;

display:flex;
flex-direction:column;
}

.back-btn{

width:50px;
height:50px;

position:absolute;

top:40px;
left:40px;

border-radius:50%;

display:flex;
align-items:center;
justify-content:center;

background:white;

border:1px solid #ddd;

font-size:22px;

color:black;

text-decoration:none;

transition:.3s;

box-shadow:0 4px 15px rgba(0,0,0,.08);

}

.back-btn:hover{

transform:translateX(-3px);

background:#f7f7f7;

}

.main-card{

width:100%;
max-width:500px;

margin:auto;

padding:40px;

border:none;

border-radius:20px;

background:rgba(255,255,255,.95);

backdrop-filter:blur(10px);

box-shadow:
0 10px 35px rgba(0,0,0,.07);

}

.profile{

width:90px;
height:90px;

margin:auto;

border-radius:50%;

display:flex;

align-items:center;
justify-content:center;

font-size:35px;

background:white;

color:#0d6efd;

border:1px solid #ddd;
}

h3{

font-size:32px;
font-weight:700;

}

.blue{

color:#0d6efd;

}

.sub{

font-size:13px;
color:#777;

}

.form-label{

font-size:12px;
font-weight:600;

}

.input-group-text{

background:white;
border-right:none;
color:#999;

}

.form-control{

border-left:none;
height:45px;

}

.form-control:focus{

box-shadow:none;
border-color:#0d6efd;

}

.btn-login{

height:45px;

font-weight:600;

border-radius:10px;
}

.google-btn{

width:100%;
height:45px;

border-radius:10px;

border:1px solid #ddd;

background:white;

display:flex;

align-items:center;
justify-content:center;

gap:10px;

font-weight:600;

transition:.3s;
}

.google-btn:hover{

background:#f7f7f7;

transform:translateY(-2px);

box-shadow:
0 6px 18px rgba(0,0,0,.08);

}

.google-btn img{

width:18px;

}

.footer{

background:#1f1f22;

padding:20px;

color:white;

text-align:center;

}

@media(max-width:768px){

.main-card{

margin:20px;
padding:25px;

}

.back-btn{

top:20px;
left:20px;

}

}

</style>

</head>

<body id="mainlogin">

<div class="main-wrapper">

<a href="index.html" class="back-btn">
<i class="bi bi-arrow-left"></i>
</a>

<div class="card main-card">

<div class="text-center">

<div class="profile">

<i class="bi bi-person-lock"></i>

</div>

<h3 class="mt-3">

Entrar na sua
<span class="blue">conta</span>

</h3>

<div class="sub">

Faça login para acessar o AutoPass

</div>

</div>

<form action="includes/processa_login.php" method="POST">

<input
type="hidden"
name="csrf_token"
value="<?= $_SESSION['csrf_token'] ?>"
>

<div class="mt-4">

<label class="form-label">

Email

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-envelope"></i>

</span>

<input
type="email"
name="email"
class="form-control"
placeholder="seu@email.com"
required
>

</div>

</div>

<div class="mt-3">

<label class="form-label">

Senha

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-lock"></i>

</span>

<input
type="password"
name="senha"
class="form-control"
placeholder="Digite sua senha"
required
>

<span class="input-group-text toggle">

<i class="bi bi-eye"></i>

</span>

</div>

</div>

<div class="d-flex justify-content-between mt-3">

<div class="form-check">

<input
class="form-check-input"
type="checkbox"
id="lembrar"
>

<label
class="form-check-label small"
for="lembrar"
>

Lembrar-me

</label>

</div>

<a href="#">

Esqueceu a senha?

</a>

</div>

<button
class="btn btn-primary w-100 mt-4 btn-login"
>

Entrar

</button>

<div class="text-center my-3 small text-muted">

ou

</div>

<button
type="button"
class="google-btn"
>

<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg">

Continuar com Google

</button>

<p class="text-center mt-4">

Não possui conta?

<a href="cadastro.php">

Criar conta

</a>

</p>

</form>

</div>

<footer class="footer">

© 2026 AutoPass • Sistema Inteligente de Estacionamento

</footer>

</div>

<script>

const toggle=document.querySelector(".toggle");
const senha=document.querySelector('input[name="senha"]');

toggle.addEventListener("click",()=>{

const icon=toggle.querySelector("i");

if(senha.type==="password"){

senha.type="text";

icon.classList.remove("bi-eye");
icon.classList.add("bi-eye-slash");

}else{

senha.type="password";

icon.classList.remove("bi-eye-slash");
icon.classList.add("bi-eye");

}

});

</script>

</body>
</html>