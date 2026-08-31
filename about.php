<?php
// What HopeBridge is and how the three kinds of account work.
require 'config.php';

$page_title = 'About';
include 'header.php';
?>

<div class="page-head">
    <div>
        <h1>About HopeBridge</h1>
        <p class="subtitle">Connecting compassion with community needs.</p>
    </div>
</div>

<div class="grid two">

    <div class="card">
        <h2>What this platform does</h2>
        <p>
            HopeBridge is one place where three groups of people meet: the donors
            who give, the families who need help, and the administrators who make
            sure the help reaches the right people.
        </p>
        <p>
            A donor picks a program and gives to it. A family applies to the same
            program and explains their situation. An administrator reads the
            application, decides, and writes back. Every donation, every
            application and every decision is written down, so nothing depends on
            somebody remembering it.
        </p>
    </div>

    <div class="figure">
        <img src="images/planting.jpg" alt="Volunteers planting young trees together"
             loading="lazy" decoding="async">
    </div>

</div>

<h2>The three kinds of account</h2>

<div class="grid">

    <div class="card with-action">
        <h3>Donors</h3>
        <p>
            Browse the programs, give to the cause you care about, and keep every
            receipt in one place. Once you have given to a program you also
            receive the progress reports written about it, so you can see what
            your money actually did.
        </p>
        <p><a href="register.php">Register as a donor</a></p>
    </div>

    <div class="card with-action">
        <h3>Beneficiaries</h3>
        <p>
            Register, fill in your details once, and an administrator checks
            whether you are eligible. After you are approved you can apply to any
            program that matches your situation, follow each application, and
            message the charity privately when you need to.
        </p>
        <p><a href="register.php">Apply for help</a></p>
    </div>

    <div class="card with-action">
        <h3>Administrators</h3>
        <p>
            Approve or refuse beneficiary profiles and applications, add and
            retire programs, publish progress reports for donors, and read the
            dashboard that shows who gives, how often, and which programs are
            closest to their goal.
        </p>
        <p><a href="login.php">Administrator login</a></p>
    </div>

</div>

<h2>How a donation travels</h2>

<div class="grid">

    <div class="card">
        <h3>1. Choose</h3>
        <p>
            A donor opens the <a href="programs.php">programs page</a>, reads who
            each program is for, and picks one.
        </p>
    </div>

    <div class="card">
        <h3>2. Give</h3>
        <p>
            The donation is recorded against that program and a receipt is
            created straight away, ready to print.
        </p>
    </div>

    <div class="card">
        <h3>3. See it work</h3>
        <p>
            When the charity publishes a report about that program, it appears on
            the donor's updates page and on the
            <a href="impact.php">impact page</a>.
        </p>
    </div>

</div>

<?php include 'footer.php'; ?>
