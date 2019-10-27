<?php
if(isset($_GET['bookId'])) {
    $bookId = $_GET['bookId'];
}else {
    // TODO Send the user back
}

require "header.php";
include "includes/getDb.inc.php";

$array = getBookByBookId($bookId);
if(isset($_SESSION['userId'])) {
    $userinfo = getUserInfo($_SESSION['userId']);
}else {
    $userinfo = NULL;
}

?>
    <script type="text/javascript">
        function switchRating() {

            var rating = document.getElementById("rating");
            var button = document.getElementById("ratingButton");
            button.innerText=rating.style.display;
            if(rating.style.display == "none" ||rating.style.display == ""||rating.style.display == null ){
                rating.style.display = "block";
                button.innerText = "Dölj betyg";
            }else if(rating.style.display == "block") {
                rating.style.display ="none";
                button.innerText = "Visa betyg";
            }
        }
    </script>
    <style>
        .container-grid{
            max-width: 900px;
            display: grid;
            grid-template-columns: 30% 60% 10%;
            grid-gap: 3px;
            padding: 3px;
            overflow: hidden;
            margin: auto;
            text-align: center;
        }
        .item1 {
            grid-column-start: 1;
            grid-column-end: 2;
        }
        .item2{
            grid-column-start: 2;
            grid-column-end: 3;
        }
        .item3{
            grid-column-start: 3;
            grid-column-end: 4;
        }
        .inputs{
            border: none;
            background:cadetblue;
            font-size: large;
            padding:12px 6px;
            min-width: 90%;
        }
        .inputs:hover {
            background:lightcoral;
        }
        .text{
            width:80%;
        }
        .button1{
            float: right;
            padding: 9px 20px;
        }
        .name{
            float:left;
            font-style: italic;
            font-size: 15px;
        }
        .comment-text{
            font-style: normal;
            font-size: 12px;
        }
        .edit-remove{
            float:right;
            font-size: 8px;
        }
    </style>
    <main class="container">
        <link rel="stylesheet" type="text/css" href="css/bookpage.css">
        <div class="container-grid">

            <div class="item1">



                <?php echo '<img src="'.$array['9'].'" alt="Books" width="250px" height="400px">'; ?>

                <ul>
                    <li>
                        <?php
                        if($userinfo == NULL){
                            //TODO error
                        }else {
                            if($userinfo['1'] == NULL) {
                                echo '  <form action="includes/addBook.inc.php?type=tbr&bookId='.$bookId.'" method="post">
                                        <input class="inputs" type="submit" value="Lägg till i \'vill läsa\' listan!">
                                        </form>';
                            }else {
                                $tmpArray = explode(';:',$userinfo['1']);
                                $tbr = false;
                                foreach ($tmpArray as $x) {
                                    if(strcasecmp($x,$bookId) == 0) {
                                        // TODO lägg till i addBook
                                        echo '  <form action="includes/addBook.inc.php?type=tbrRemove&bookId='.$bookId.'" method="post">
                                        <input class="inputs" type="submit" value="Ta bort från \'vill läsa\' listan!">
                                        </form>';
                                        $tbr = true;
                                        break;
                                    }
                                }
                                if($tbr == false) {
                                    echo '  <form action="includes/addBook.inc.php?type=tbr&bookId='.$bookId.'" method="post">
                                            <input class="inputs" type="submit" value="Lägg till i \'vill läsa\' listan!">
                                            </form>';
                                }
                            }
                        }

                        ?>
                    </li>
                    <li>
                        <?php
                        if($userinfo == NULL){
                            //TODO error
                        }else {
                            if($userinfo['2'] == NULL) {
                                echo '      <form action="includes/addBook.inc.php?type=hr&bookId='.$bookId.'" method="post">
                                            <input class="inputs" type="submit" value="Lägg till i \'har läst\' listan!">
                                            </form>';
                            }else {
                                $tmpArray = explode(';:',$userinfo['2']);
                                $read = false;
                                foreach ($tmpArray as $x) {
                                    if(strcasecmp($x,$bookId) == 0) {
                                        // TODO lägg till i addBook
                                        echo '      <form action="includes/addBook.inc.php?type=hrRemove&bookId='.$bookId.'" method="post">
                                                    <input class="inputs" type="submit" value="Ta bort från \'har läst\' listan!">
                                                    </form>';
                                        $read = true;
                                        break;
                                    }
                                }
                                if($read == false) {
                                    echo '  <form action="includes/addBook.inc.php?type=hr&bookId='.$bookId.'" method="post">
                                            <input class="inputs" type="submit" value="Lägg till i \'vill läsa\' listan!">
                                            </form>';
                                }
                            }
                        }

                        ?>
                    </li>
                </ul>
            </div>

            <div class="item2">

                <h3><?php echo $array['1'];?></h3>
                <h4><?php echo $array['2']?></h4>

                <!-- vi måste hitta summeringen någonstans så att vi kan lägga in den -->

                <div class="summarise">
                    <?php echo $array['5']; ?>
                </div>

                <!-- lägga till databs så att ratingen tas från den och läggs in där när någon klickar på stjärnorna -->
                <ul>
                    <li>
                        <div id="ratingBox" >
                            <button type="button" onclick="switchRating()" id="ratingButton">Visa betyg</button>
                            <p id="rating">
                                <?php
                                if ($array['11'] == NULL) {
                                    echo 'Inga betyg för denna bok än';
                                }else {
                                    echo $array['11'];
                                }
                                ?>
                            </p>
                        </div>
                    </li>
                    <li>
                        <form action="includes/addBook.inc.php?type=rating&bookId=<?php echo $bookId?>" method="post" class="rate">
                            <?php
                            function isBookRated($bookId,$userinfo){
                                if(isset($userinfo['4'])) {
                                    if(!contains(';:',$userinfo['4']) && strcasecmp($userinfo['4'],$bookId) == 0) {
                                        return $userinfo['5'];
                                    }else if(!contains(';:',$userinfo['4']) && strcasecmp($userinfo['4'],$bookId) != 0){
                                        return false;
                                    }else {
                                        $ratedBooksId = explode(";:" , $userinfo['4']);
                                        for($x = 0; $x < sizeof($ratedBooksId); $x++){
                                            if(strcasecmp($bookId,$ratedBooksId[strval($x)]) == 0) {
                                                $tmpArray = explode(';:',$userinfo['5']);
                                                return $tmpArray[strval($x)];
                                            }
                                        }
                                        return false;
                                    }
                                }
                            }
                            $bookRated = isBookRated($bookId,$userinfo);
                            if($bookRated == false){
                                echo '  <input type="radio" id="star5" name="rate" value="5" onclick="this.form.submit();"><label for="star5" title="Perfekt" ></label>
                                        <input type="radio" id="star4" name="rate" value="4" onclick="this.form.submit();"><label for="star4" title="Bra"></label>
                                        <input type="radio" id="star3" name="rate" value="3" onclick="this.form.submit();"><label for="star3" title="Okej"></label>
                                        <input type="radio" id="star2" name="rate" value="2" onclick="this.form.submit();"><label for="star2" title="Inte så bra"></label>
                                        <input type="radio" id="star1" name="rate" value="1" onclick="this.form.submit();"><label for="star1" title="Väldigt dålig"></label>';
                            }
                            else {
                                if ($bookRated == 1) {
                                    echo '  <input type="radio" id="star5" name="rate" value="5" onclick="this.form.submit();"><label for="star5" title="Perfekt" ></label>
                                        <input type="radio" id="star4" name="rate" value="4" onclick="this.form.submit();"><label for="star4" title="Bra"></label>
                                        <input type="radio" id="star3" name="rate" value="3" onclick="this.form.submit();"><label for="star3" title="Okej"></label>
                                        <input type="radio" id="star2" name="rate" value="2" onclick="this.form.submit();"><label for="star2" title="Inte så bra"></label>
                                        <input type="radio" id="star1" name="rate" value="1" onclick="this.form.submit();" checked="checked"><label for="star1" title="Väldigt dålig"></label>';
                                } else if ($bookRated == 2) {
                                    echo '  <input type="radio" id="star5" name="rate" value="5" onclick="this.form.submit();"><label for="star5" title="Perfekt" ></label>
                                        <input type="radio" id="star4" name="rate" value="4" onclick="this.form.submit();"><label for="star4" title="Bra"></label>
                                        <input type="radio" id="star3" name="rate" value="3" onclick="this.form.submit();"><label for="star3" title="Okej"></label>
                                        <input type="radio" id="star2" name="rate" value="2" onclick="this.form.submit();" checked="checked"><label for="star2" title="Inte så bra"></label>
                                        <input type="radio" id="star1" name="rate" value="1" onclick="this.form.submit();"><label for="star1" title="Väldigt dålig"></label>';
                                } else if ($bookRated == 3) {
                                    echo '  <input type="radio" id="star5" name="rate" value="5" onclick="this.form.submit();"><label for="star5" title="Perfekt" ></label>
                                        <input type="radio" id="star4" name="rate" value="4" onclick="this.form.submit();"><label for="star4" title="Bra"></label>
                                        <input type="radio" id="star3" name="rate" value="3" onclick="this.form.submit();" checked="checked"><label for="star3" title="Okej"></label>
                                        <input type="radio" id="star2" name="rate" value="2" onclick="this.form.submit();"><label for="star2" title="Inte så bra"></label>
                                        <input type="radio" id="star1" name="rate" value="1" onclick="this.form.submit();"><label for="star1" title="Väldigt dålig"></label>';
                                } else if ($bookRated == 4) {
                                    echo '  <input type="radio" id="star5" name="rate" value="5" onclick="this.form.submit();"><label for="star5" title="Perfekt" ></label>
                                        <input type="radio" id="star4" name="rate" value="4" onclick="this.form.submit();" checked="checked"><label for="star4" title="Bra"></label>
                                        <input type="radio" id="star3" name="rate" value="3" onclick="this.form.submit();"><label for="star3" title="Okej"></label>
                                        <input type="radio" id="star2" name="rate" value="2" onclick="this.form.submit();"><label for="star2" title="Inte så bra"></label>
                                        <input type="radio" id="star1" name="rate" value="1" onclick="this.form.submit();"><label for="star1" title="Väldigt dålig"></label>';
                                } else if ($bookRated == 5) {
                                    echo '  <input type="radio" id="star5" name="rate" value="5" onclick="this.form.submit();" checked="checked"><label for="star5" title="Perfekt" ></label>
                                        <input type="radio" id="star4" name="rate" value="4" onclick="this.form.submit();"><label for="star4" title="Bra"></label>
                                        <input type="radio" id="star3" name="rate" value="3" onclick="this.form.submit();"><label for="star3" title="Okej"></label>
                                        <input type="radio" id="star2" name="rate" value="2" onclick="this.form.submit();"><label for="star2" title="Inte så bra"></label>
                                        <input type="radio" id="star1" name="rate" value="1" onclick="this.form.submit();"><label for="star1" title="Väldigt dålig"></label>';
                                }
                            }
                            ?>
                        </form>
                    </li>
                </ul>
                <!-- ladda kommentarer från en databas som hör ihop med boken -->

                <div class="commentfield">
                    Kommentarer:
                    <?php

                    // TODO lägga till så att ;: och :: är illegala tecken när man skriven en kommentar
                    // TODO kanske ändra så att man inte får all info i getuserinfo
                    if($array['12'] == NULL) {
                        echo '<div class="comment">Var den första att skriva en kommentar för denna bok</div>';
                    }else if (!contains(';:',$array['12'])) {
                        $comment = explode('::',$array['12']);
                        $commentUserinfo = getUserInfo($comment['0']);
                        echo '<div class="comment"><div class="name">'.$commentUserinfo['8'].' '.$commentUserinfo['9'].'</div>';
                        if(isset($_SESSION['userId']) && $_SESSION['userId'] == $comment['0']) {
                            echo '<div class="edit-remove"><a href="includes/addBook.inc.php?type=removeComment&bookId='.$bookId.'&comment='.$array['12'].'">radera</a></div>';
                        }
                        echo '<br><div class="comment-text">'.$comment['1'].'</div></div>';
                    }else {
                        $comments = explode(';:',$array['12']);
                        foreach ($comments as $x) {
                            $comment = explode('::',$x);
                            $commentUserinfo = getUserInfo($comment['0']);
                            echo '<div class="comment"><div class="name">'.$commentUserinfo['8'].' '.$commentUserinfo['9'].'</div>';
                            if($_SESSION['userId'] == $comment['0']) {
                                echo '<div class="edit-remove"><a href="includes/addBook.inc.php?type=removeComment&bookId='.$bookId.'&comment='.$x.'">radera</a></div>';
                            }
                            echo '<br><div class="comment-text">'.$comment['1'].'</div></div>';
                        }
                    }
                    if(isset($_SESSION['userId'])) {
                        // TODO ta hand om tom textarea här
                        // TODO lägga till så att man kan edita och ta bor kommentarer
                        echo '<form action="includes/addBook.inc.php?type=comment&bookId='.$bookId.'" method="post">
                            Kommentera:<br>
                            <textarea class="text" name="comment"></textarea>
                            <button type="submit" class="button1">Publicera</button>
                        </form>';
                    }else {
                        // TODO länka till inlogning och skapa konto
                        echo 'Logga in eller skapa ett konto för att skriva en kommentar';
                    }


                    ?>

                </div>
            </div>

            <div class="item3">

            </div>
        </div>
    </main>

<?php
require "footer.php";
?>
<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 2019-06-22
 * Time: 13:31
 */