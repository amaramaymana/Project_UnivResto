<?php
session_start();
include("../config/db.php");
date_default_timezone_set('Africa/Tunis');

error_reporting(E_ALL);
ini_set('display_errors', 1);


if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}

// PHPMailer 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../assets/phpmailer/Exception.php';
require_once '../assets/phpmailer/PHPMailer.php';
require_once '../assets/phpmailer/SMTP.php';

// config mail
$mailConfig = include("../config/mail.php");

/* ===============================
   📧 SEND TO ALL STUDENTS
================================ */
/*
if(isset($_POST['send_reminder'])){

    // 
    $students = $pdo->query("
        SELECT * FROM users WHERE role='etudiant'
    ")->fetchAll();

    foreach($students as $s){

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $mailConfig['email'];
        $mail->Password = $mailConfig['password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($mailConfig['email'], 'Resto Univ');
        $mail->addAddress($s['email']);

        $mail->isHTML(true);
        $mail->Subject = ' Rappel Restaurant Universitaire';

        $mail->Body = "
        <h2>Bonjour ".$s['nom']."</h2>
        <p>📌 N'oubliez pas :</p>
        <ul>
            <li>🎟 2 tickets</li>
            <li>🪪 Carte foyer</li>
        </ul>
        <p style='color:gray;'>Resto Universitaire</p>
        ";

        $mail->send();

    } catch(Exception $e){
        error_log("Mail error: ".$mail->ErrorInfo);
    }
}
}
*/
/* ===============================
   📊 STATISTIQUES
================================ */

$totalMeals = $pdo->query("SELECT COUNT(*) FROM meals")->fetchColumn();

$totalConf = $pdo->query("SELECT COUNT(*) FROM confirmations")->fetchColumn();

/* ===============================
   👨‍🎓 STUDENTS
================================ */

$students = $pdo->query("
SELECT * 
FROM users 
WHERE role='etudiant'
ORDER BY nom
")->fetchAll();
// Déjeuner اليوم
$dejeuner = $pdo->query("
SELECT COUNT(*) 
FROM confirmations c
JOIN meals m ON c.meal_id = m.id
WHERE m.type='dejeuner'
AND m.date = CURDATE()
")->fetchColumn();

// Dinerوم
$diner = $pdo->query("
SELECT COUNT(*) 
FROM confirmations c
JOIN meals m ON c.meal_id = m.id
WHERE m.type='diner'
AND m.date = CURDATE()
")->fetchColumn();

// Total étudiants
$totalStudents = $pdo->query("
SELECT COUNT(*) 
FROM users 
WHERE role='etudiant'
")->fetchColumn();

// Confirmations ا
$totalConfirmations = $pdo->query("
SELECT COUNT(*) 
FROM confirmations c
JOIN meals m ON c.meal_id = m.id
WHERE m.date = CURDATE()
")->fetchColumn();

// Repas 
$totalMeals = $pdo->query("
SELECT COUNT(*) 
FROM meals 
WHERE date = CURDATE()
")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">
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

/* HERO */
.hero{
width:100%;
max-width:100%;
background:var(--card);
border-radius:18px;
overflow:hidden;
display:flex;
border:1px solid rgba(255,255,255,0.05);
box-shadow:0 25px 80px rgba(0,0,0,0.45);
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
position:relative;
overflow:hidden;
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

/* RIGHT */
.right-side{
width:50%;
padding:40px 50px;
display:flex;
flex-direction:column;
justify-content:center;
}
/* NAV */
.navbar{
  display:flex;
  justify-content:space-between;
  margin-bottom:50px;
  flex-wrap:wrap;
  gap:15px;
}

/* NAVBAR */
.logo{
  margin-bottom:18px;
  letter-spacing:1px;
  color:var(--primary);
}

.logo span{
  display:block;
  font-size:0.85rem;
  color:var(--muted);
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

/* TITLES */

h1{
color:var(--primary);
margin-bottom:15px;
}

h2{
color:var(--primary);
margin:20px 0;
}

/* STATS */

.stats{
background:#1e1e1e;
padding:15px;
border-radius:10px;
margin-bottom:20px;
}

/* BUTTON */

.btn{
padding:12px;
background:var(--primary);
border:none;
border-radius:8px;
font-weight:600;
cursor:pointer;
transition:0.3s;
color:#222;
text-decoration:none;
display:inline-block;
}

.btn:hover{
background:var(--primary-light);
transform:translateY(-2px);
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:#1e1e1e;
border-radius:10px;
overflow:hidden;
}

th,td{
padding:12px;
border-bottom:1px solid #333;
text-align:center;
}

th{
background:#ffc107;
color:#222;
}

/* MESSAGE */

.message{
color:lightgreen;
margin:15px 0;
}

/* DECORATIONS */

.deco{
position:absolute;
opacity:0.18;
z-index:1;
fill:#ffc107;
stroke:#ffc107;
}

.deco.one{
top:150px;
right:130px;
width:55px;
}

.deco.two{
top:290px;
left:35px;
width:65px;
}

.deco.three{
bottom:120px;
right:90px;
width:60px;
}

.deco.four{
bottom:60px;
left:130px;
width:70px;
}

.deco.five{
top:120px;
left:20px;
width:26px;
}

.deco.six{
bottom:90px;
right:250px;
width:28px;
}
.toast{
    position: fixed;
    top: 20px;
    right: 20px;
    background: #333;
    padding: 15px 20px;
    border-radius: 8px;
    color: #fff;
    opacity: 0;
    transform: translateY(-20px);
    transition: 0.4s;
    z-index: 999;
}

.toast.show{
    opacity: 1;
    transform: translateY(0);
}

/* RESPONSIVE */

@media(max-width:900px){

.hero{
flex-direction:column;
}

.left-side,
.right-side{
width:100%;
}

.left-side{
height:250px;
}

table,thead,tbody,th,td,tr{
display:block;
}

td{
padding-left:50%;
position:relative;
text-align:right;
}

td::before{
content:attr(data-label);
position:absolute;
left:10px;
color:#ffc107;
font-weight:bold;
}

}

/* PAGE TRANSITION */

body{
opacity:0;
transform:translateY(20px);
transition:all 0.4s ease;
}

body.fade-in{
opacity:1;
transform:translateY(0);
}

body.fade-out{
opacity:0;
transform:translateY(-20px);
}
</style>
</head>
<body>

<div class="hero">

    <!-- LEFT IMAGE -->
    <div class="left-side">
        <img src="../assets/images/admin.jpeg">
        <div class="curve"></div>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="right-side">

        <!-- NAVBAR -->
        <nav class="navbar">
            <div class="logo">RestoUniv</div>

            <ul class="nav-links">
                <li><a href="../auth/logout.php">Déconnexion</a></li>
                <li><a href="chefs_list.php"> Liste des chefs</a></li>
            </ul>
        </nav>

        <h1>Admin Dashboard</h1>

        <div class="stats">
            <p>Total repas : <?php echo $totalMeals; ?></p>
            <p>Total confirmations : <?php echo $totalConf; ?></p>
        </div>
        <?php if(!empty($message)): ?>
        <?php endif; ?>

        <h2>Liste des étudiants</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email / Action</th>
            </tr>

            <?php foreach($students as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['nom']) ?></td>
                <td>
                    <?= htmlspecialchars($s['email']) ?>
                    <br>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</div>
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
window.onload = () => {
    let toast = document.getElementById("toast");
    if(toast){
        setTimeout(()=> toast.classList.add("show"),100);
        setTimeout(()=> toast.classList.remove("show"),3000);
    }
}
</script>
<script id="pageTransition">
document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("fade-in");

  
    link.addEventListener("click", function(e) {

      // ignore external / empty links
      if (this.href && !this.href.includes("#")) {
        e.preventDefault();

        document.body.classList.remove("fade-in");
        document.body.classList.add("fade-out");

        setTimeout(() => {
          window.location = this.href;
        }, 300);
      }
    });
});
</script>
</body>
</html>