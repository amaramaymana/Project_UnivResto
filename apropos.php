<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>À propos - Resto Univ</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">

<style>
:root{
  --primary:#ffc107;
  --primary-light:#ffcf33;
  --dark:#1f1f1f;
  --card:#2b2b2b;
  --input:#3a3a3a;
  --text:#f5f5f5;
  --muted:#cfcfcf;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  scroll-behavior:smooth;
}

body{
  font-family:'Inter', sans-serif;
  background:linear-gradient(135deg,#2b2b2b,#3a3a3a);
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
  padding:25px;
  color:var(--text);
}

/* CONTAINER */
.hero{
  width:95%;
  max-width:1300px;
  background:var(--card);
  border-radius:14px;
  overflow:hidden;
  display:flex;
  box-shadow:0 25px 60px rgba(0,0,0,0.45);
  animation: fadeIn 0.8s ease;
  position:relative;
}
@keyframes fadeIn{
   from{ 
    opacity:0; 
    transform:translateY(30px); 
  } to{ 
    opacity:1; 
    transform:translateY(0); 
  } 
}
/* LEFT */
.left-side{
  width:50%;
  position:relative;
  overflow: hidden;
  background:var(--dark);
}

.left-side img{
  width:100%;
  height:100%;
  object-fit:cover;
  filter:brightness(0.85);
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
  z-index: 2;
}
.right-side{
  width: 50%;
  position: relative;
  padding: 35px 60px 40px 70px; 
  z-index: 3;
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
  margin-bottom:50px;
  flex-wrap:wrap;
  gap:15px;
}

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

/* CONTENT */
h1{
  color:var(--primary);
  margin-bottom:20px;
}

p{
  margin-bottom:15px;
  line-height:1.7;
  color:#ddd;
}

.highlight{
  color:var(--primary);
  font-weight:500;
}

    /* ERROR */
    .error{
      color:#ff4d4d;
      margin-bottom:10px;
    }

    /* SUCCESS */
    .success{
      color:#4caf50;
      margin-bottom:10px;
    }
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
}
/* PAGE TRANSITION */
body {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.4s ease;
}

body.fade-in {
  opacity: 1;
  transform: translateY(0);
}

body.fade-out {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
</head>

<body>

<div class="hero">

  <!-- IMAGE -->
  <div class="left-side">
    <img src="assets/images/apropos.jpeg">
    <div class="curve"></div>
  </div>

  <!-- CONTENT -->
  <div class="right-side">

    <!-- NAVBAR -->
    <nav class="navbar">
      <div class="logo">RestoUniv</div>
          <ul class="nav-links">
          <li><a href="index.php">Acceuil</a></li>
          </ul>
    </nav>
    <!-- TEXT -->
    <h1>À propos du projet</h1>

    <p>
      Ce projet a été réalisé dans le cadre d’un <span class="highlight">projet universitaire</span>.
      En tant qu’étudiant, j’ai souvent constaté une problématique fréquente dans les restaurants universitaires :
      <span class="highlight">le gaspillage alimentaire</span> ainsi que le manque d’organisation.
    </p>

    <p>
      En effet, il m’arrivait d’arriver en retard au restaurant et de ne plus trouver de repas disponible,
      tandis que dans d’autres situations, une grande quantité de nourriture était jetée faute de consommation.
    </p>

    <p>
      C’est à partir de cette observation qu’est née l’idée de développer une solution digitale permettant
      aux étudiants de <span class="highlight">confirmer leur présence à l’avance</span>.
    </p>

    <p>
      Grâce à ce système, les chefs peuvent préparer une quantité précise de repas,
      ce qui permet de <span class="highlight">réduire le gaspillage</span>,
      d’optimiser les ressources et d’améliorer l’organisation du service.
    </p>

    <p>
      L’objectif principal de ce projet est de contribuer à un environnement plus durable,
      tout en offrant une meilleure expérience aux étudiants.
    </p>

    <p>
      Cependant, le développement de cette application n’a pas été sans difficultés.
      Parmi les principaux défis rencontrés :
    </p>

    <p>
      • La gestion des <span class="highlight">envois d’emails</span> pour notifier les utilisateurs  <br>
      • La mise en place des <span class="highlight">conditions de confirmation et d’annulation</span>  <br>
      • La gestion des rôles (<span class="highlight">admin, chef, étudiant</span>)  <br>
      • Et l’organisation globale du projet (base de données, sécurité, sessions…)
    </p>

    <p>
      Malgré ces défis, ce projet m’a permis de développer mes compétences en développement web
      et de mieux comprendre les problématiques réelles du terrain.
    </p>
    </div>
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
</body>
<script id="pageTransition">
document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("fade-in");

  document.querySelectorAll("a").forEach(link => {
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
});
</script>
</body>
</html>