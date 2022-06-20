<?php
if($_SESSION['env'] == 'dev'){
?>
<footer>
    Testuj gracza
    <form action="">
        <input type="number" name="test_player_id" placeholder="player_id">
        <input type="submit" class="test-player-btn" value="Ok">
        <button type="button" class="stop-interval-btn">stop interval</button>
    </form>
</footer>
<script src="js/testing.js"></script>
<?php
}
?>

<script src="js/game.js"></script>

<script src="js/dynamicEvents.js"></script>
<script src="js/gameStyles.js"></script>
</body>
</html>