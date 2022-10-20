// the navbar is strongly inspired by the the navbar of a free html template I found online (https://www.free-css.com/free-css-templates/page283/webuild), however only the navbar and button style are taken from this template

// this is the html code for the navbar, implemented this way, to have the same navbar for all the pages
// without needing to change them all individually

// Navbar when logged in
var navbar_loggedin = ` 
<nav class="navbar navbar-expand-lg bg-dark bg-light-radial navbar-dark py-3 py-lg-0">
    <a href="index.php" class="navbar-brand">
        <h1 class="m-0 display-5 text-uppercase text-white"><img src="img/ICATH_logo.jpg" alt="ITAC_image" width="100" height="100"/>ICATH'2022</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0 px-0">
            <a href="index.php" class="nav-item nav-link">Home</a>
            <div class="nav-item dropdown">
                <a href="publications.php" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Publications</a>
                <div class="dropdown-menu m-0 bg-dark bg-light-radial">
                    <a href="publications.php#topics" class="dropdown-item text-white">Topics</a>
                    <a href="publications.php#publ" class="dropdown-item text-white">Publications</a>
                    <a href="publications.php#author_guidelines" class="dropdown-item text-white">Author Guidelines</a>
                    <a href="publications.php#deadlines" class="dropdown-item text-white">Deadlines</a>
                    <a href="registration.php" class="dropdown-item text-white">Register</a>
                </div>
            </div>
            <a href="new_publications.php" class="nav-item nav-link">New Papers</a>
            <a href="contact.php" class="nav-item nav-link">Contact</a>
            <a href="php/server.php?logout='1'" class="nav-item nav-link">Log out</a>
        </div>
    </div>
</nav>`

// inserting navbar in beginning of body
document.getElementById('navbar').innerHTML = navbar_loggedin
