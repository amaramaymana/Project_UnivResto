<?php
session_start();
include("../config/db.php");
date_default_timezone_set('Africa/Tunis');
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'etudiant'){
    header("Location: ../auth/login.php");
    exit();
}

$user = $_SESSION['id'];
$message = "";

/* ACTION CONFIRM */
if(isset($_GET['confirm'])){

    $meal_id = intval($_GET['confirm']);

    $stmt = $pdo->prepare("SELECT * FROM meals WHERE id=?");
    $stmt->execute([$meal_id]);
    $meal = $stmt->fetch();

    if($meal){

        $check = $pdo->prepare("SELECT * FROM confirmations WHERE user_id=? AND meal_id=?");
        $check->execute([$user,$meal_id]);

        if($check->rowCount() == 0){

            $pdo->prepare("INSERT INTO confirmations(user_id,meal_id,created_at) VALUES(?,?,NOW())")
                ->execute([$user,$meal_id]);
            header("Location: dashboard.php");
            exit();
        }
    }
}

/* ACTION CANCEL */
if(isset($_GET['cancel'])){
    $meal_id = $_GET['cancel'];

    $check = $pdo->prepare("SELECT * FROM confirmations WHERE user_id=? AND meal_id=?");
    $check->execute([$user,$meal_id]);

    if($check->rowCount() > 0){
        $conf = $check->fetch();

        $now = new DateTime();
        $confirm_time = new DateTime($conf['created_at']);

        $limit = clone $confirm_time;
        $limit->modify('+30 minutes');

        if($now > $limit){
            $message = " Annulation impossible (30 min dépassées)";
        } else {
            $pdo->prepare("DELETE FROM confirmations WHERE user_id=? AND meal_id=?")
                ->execute([$user,$meal_id]);

            $message = "Réservation annulée";
            header("Location: dashboard.php");
            exit();
        }
    }
}
$meals = $pdo->query("SELECT * FROM meals ORDER BY date ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Étudiant</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>

:root{
--primary: #ffc107;
--primary-light: #ffcf33;
--dark: #1f1f1f;
--card: #2b2b2b;
--input: #3a3a3a;
--text: #f5f5f5;
--muted: #cfcfcf;
}

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:linear-gradient(135deg,#2b2b2b,#3a3a3a);
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
padding:20px;
color:var(--text);
}

.hero{
  width:980%;
  max-width:100%;
  background:var(--card);
  border-radius:18px;
  overflow:hidden;
  display:flex;
  border:1px solid var(--stroke);
  box-shadow:0 25px 80px rgba(0,0,0,0.45);
  backdrop-filter:blur(18px);
  animation: fadeIn 0.8s ease;
  position:relative;
}
@keyframes fadeIn{ 
  from{ 
  opacity:0; 
  transform:translateY(30px); 
} 
to{ 
  opacity:1; 
  transform:translateY(0); 
} 
}
/* LEFT */
.left-side{
  width:50%;
  position: relative;
  overflow: hidden;
  background:var(--dark);
}

.left-side img{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(0.8);
}

/* CURVE */
.curve{
  position:absolute;
  top:-40px;
  right:-80px;
  width:180px;
  height:110%;
  background:var(--card);
  border-left:6px solid rgba(255,193,7,0.5);
  border-radius:50%;
  
}
.deco {
    position: absolute;
    opacity: 0.18; 
    z-index: 1; 
    fill: #ffc107; 
    stroke: #ffc107; 
} 
.deco.one { 
    top: 150px; 
    right: 130px; 
    width: 55px; 
}
 .deco.two { 
    top: 290px; 
    left: 35px; 
    width: 65px; 
}
 .deco.three { 
    bottom: 120px;
    right: 90px; 
    width: 60px; 
} .deco.four { 
    bottom: 60px; 
    left: 130px; 
    width: 70px; 
} 
.deco.five { 
    top: 120px; 
    left: 20px; 
    width: 26px; 
} 
/* RIGHT */
.deco.six { 
    bottom: 90px; 
    right: 250px; 
    width: 28px; 
}
/* RIGHT */
.right-side{
  width:50%;
  padding:40px 50px;
  display:flex;
  flex-direction:column;
  justify-content:center;
}


/* NAVBAR */

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.logo{
  margin-bottom:18px;
  letter-spacing:1px;
  color:var(--primary);
}

.nav-links{
  display:flex;
  gap:25px;
  list-style:none;
}

.nav-links a{
  text-decoration:none;
  color:var(--text);
  font-size:0.95rem;
  position:relative;
  transition:0.3s;
}

.nav-links a::after{
  content:"";
  position:absolute;
  left:0;
  bottom:-3px;
  width:0%;
  height:2px;
  background:var(--primary);
  transition:0.3s;
}

.nav-links a:hover{
  color:var(--primary);
}

.nav-links a:hover::after{
  width:100%;
}

/* CARD */

.card{
background: #333;
padding:20px;
border-radius:12px;
margin-bottom:20px;
box-shadow:0 10px 20px rgba(0,0,0,0.3);
}

.card h3{
color: #ffc107;
margin-bottom:8px;
}

/* BUTTON */

.btn{
padding:10px 15px;
border:none;
border-radius:8px;
text-decoration:none;
font-size:14px;
display:inline-block;
margin-top:10px;
}

.confirm{
background:#ffc107;
color:#000;
}

.cancel{
background:#ff4d4d;
color:white;
}

.btn:hover{
opacity:.8;
}

/* TOAST */

.toast{
position:fixed;
top:20px;
right:20px;
background:#333;
padding:15px 20px;
border-radius:8px;
opacity:0;
transform:translateY(-20px);
transition:.4s;
}

.toast.show{
opacity:1;
transform:translateY(0);
}

/* RESPONSIVE */

@media(max-width:900px){
.hero{
flex-direction:column;
}

.left-side{
height:250px;
width:100%;
}

.right-side{
width:100%;
}
}

</style>
</head>

<body>

<div class="hero">

<div class="left-side">
<img src="../assets/images/etudiant.jpeg">
<div class="curve"></div>
</div>

<div class="right-side">

<div class="navbar">
<div class="logo">RestoUniv</div>

<ul class="nav-links">
<li><a href="../auth/logout.php">Déconnexion</a></li>
<li><a href="../apropos.php">À propos</a></li>
<li><a href="../contact.php">Contact</a></li>
</ul>
</div>

<h2 style="color:#ffc107;margin-bottom:20px;">
Bienvenue Etudiant
</h2>

<table style="width:100%; border-collapse:collapse; text-align:center;">
<tr style="background:#ffc107; color:black;">
    <th>Entrée</th>
    <th>Plat</th>
    <th>Dessert</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php foreach($meals as $meal): 

$check = $pdo->prepare("SELECT * FROM confirmations WHERE user_id=? AND meal_id=?");
$check->execute([$user,$meal['id']]);
$confirmed = $check->rowCount() > 0;

?>

<tr style="background:#333;">
    <td><?= $meal['entree'] ?></td>
    <td><?= $meal['plat'] ?></td>
    <td><?= $meal['dessert'] ?></td>
    <td><?= $meal['date'] ?></td>

    <td>
        <?php if(!$confirmed): ?>
            <a href="?confirm=<?= $meal['id'] ?>" class="btn confirm">Confirmer</a>
        <?php else: ?>
            <a href="?cancel=<?= $meal['id'] ?>" class="btn cancel">Annuler</a>
        <?php endif; ?>
    </td>
</tr>

<?php endforeach; ?>
</table>

<?php if($message): ?>
<div id="toast" class="toast"><?= $message ?></div>
 <!-- Icône feuille -->
      <svg class="deco one" viewBox="0 0 64 64">
        <path d="M32 58C20 50 14 39 14 27C14 17 21 10 31 10C37 10 41 13 44 18C47 13 51 10 57 10C67 10 74 17 74 27C74 39 68 50 56 58"
              transform="translate(-12 -4)"
              fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>

      <!-- Icône blé -->
      <svg class="deco two" viewBox="0 0 64 64">
        <path d="M32 56V14M32 22c-6 0-10-4-10-10M32 28c-6 0-10-4-10-10M32 34c-6 0-10-4-10-10M32 22c6 0 10-4 10-10M32 28c6 0 10-4 10-10M32 34c6 0 10-4 10-10"
              fill="none" stroke-width="2.5" stroke-linecap="round"/>
      </svg>

      <!-- Icône cloche repas -->
      <svg class="deco three" viewBox="0 0 64 64">
        <path d="M10 42h44M18 42a14 14 0 0 1 28 0M32 20v-4"
              fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>

      <!-- Icône assiette -->
      <svg class="deco four" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="18" fill="none" stroke-width="2.5"/>
        <circle cx="32" cy="32" r="7" fill="none" stroke-width="2.5"/>
      </svg>

      <!-- Petit cercle -->
      <svg class="deco five" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="12" fill="none" stroke-width="2.5"/>
        <circle cx="32" cy="32" r="3" stroke="none"/>
      </svg>

      <!-- Petit cercle -->
      <svg class="deco six" viewBox="0 0 64 64">
        <circle cx="32" cy="32" r="12" fill="none" stroke-width="2.5"/>
        <circle cx="32" cy="32" r="3" stroke="none"/>
      </svg>

</div>
<script>
setTimeout(()=>{
document.getElementById('toast').classList.add('show');
},100);

setTimeout(()=>{
document.getElementById('toast').classList.remove('show');
},3000);
</script>

<?php endif; ?>

</div>

<script>

let lastCheck = new Date().toISOString();

setInterval(() => {
fetch("check_notifications.php?last=" + lastCheck)
.then(res => res.json())
.then(data => {

if(data.length > 0){
lastCheck = new Date().toISOString();

data.forEach(meal => {
showToast("🍽 Nouveau repas ajouté !");
});
}

});
}, 5000);

function showToast(msg){
let toast = document.createElement("div");
toast.className = "toast show";
toast.innerText = msg;

document.body.appendChild(toast);

setTimeout(()=>{
toast.remove();
},3000);
}

</script>

</body>
</html>