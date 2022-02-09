<?php include_once "header.php"; ?>

<section>
    <div class="main-div">
        <h1>Saper competitive</h1>
        <form action="">
            <input type="submit" class="btn-main new-game-btn" value="Nowa gra">
        </form>
        <input type="submit" class="btn-main join-game-btn" value="Dołącz do gry">
<!--        <h1>Wprowadź ID gry</h1>-->
<!--        <form>-->
<!--            <input type='number' class='join-id-input'><br>-->
<!--            <input type='submit' class='btn-main join-id-btn' value='Dołącz'>-->
<!--         </form>;-->
    </div>
    <div class="changing-div hidden">

    </div>
</section>

<?php
    include_once "templates.html";
    include_once "footer.php";
    ?>