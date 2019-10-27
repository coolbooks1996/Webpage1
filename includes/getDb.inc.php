<?php
require "dbh.inc.php";

function getConnection(){
    // Starts a connection with the db and returns the connection.
    $servername = "localhost";
    $dBUsername = "root";
    $dBPassword = "";
    $dBName = "loginsystembok";

    $conn = mysqli_connect($servername, $dBUsername,$dBPassword,$dBName);

    if(!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }
    return $conn;
}
function getUserInfo($userId) {
    // Returns array with userinfo, false if sql error
    // userId is  the id defining the user from session
    $conn = getConnection();
    $sql = "SELECT * FROM info WHERE uid=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","userId=".$userId);
        return false;
    } else {
        mysqli_stmt_bind_param($stmt, 's', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $array;
    }
}
function getLists($userId) {
    // Returns user created lists, false if sql error
    // userId is  the id defining the user from session
    $conn = getConnection();
    $sql = "SELECT * FROM lists WHERE uid=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","userId=".$userId);
        return false;
    } else {
        mysqli_stmt_bind_param($stmt, 's', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $array;
    }
}
function getCategories($TABLE_NAME) {
    // Returns the categories for a table with TABLE_NAME, returns false if sql error.
    // TABLE_NAME is the tables name in db
    $conn = getConnection();
    $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","table_name=".$TABLE_NAME);
        return false;
    } else {
        mysqli_stmt_bind_param($stmt,"s",$TABLE_NAME);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $categories = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
            array_push($categories,$row['3']);
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $categories;
    }
}
function getBookByGoogleId($googleId) {
    // returns book information, returns false if sql error
    // googleId is the id google uses to identify its books
    $conn = getConnection();
    $sql = "SELECT * FROM books WHERE googleId=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","googleId=".$googleId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $googleId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $array;
    }
}
function getBookByBookId($bookId) {
    // returns book information, returns false if sql error
    // bookId is our id for the books
    $conn = getConnection();
    $sql = "SELECT * FROM books WHERE bookId=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","bookId=".$bookId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $bookId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $array;
    }
}
function getBookByTitleNAuthor($title,$author) {
    // returns book information, returns false if sql error
    // title is the title of the book and author is the books author
    $conn = getConnection();
    $sql = "SELECT * FROM books WHERE title=? AND author=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","title=".$title,"author=".$author);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "ss", $title,$author);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $array;
    }
}
function getCommentsByBookId($bookId) {
    // returns comment for book, returns false if sql error
    // bookId is our id for the books
    $conn = getConnection();
    $sql = "SELECT comments FROM books WHERE bookId=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","bookId=".$bookId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $bookId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_row($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $row['0'];
    }
}
// TODO getBookID

function addToBookTable($title,$authors,$publisher,$publishedDate,$description,$isbn13,$isbn10,$smallthumbnail,$thumbnail,$textsnippet,$googleId){
    if ($googleId != NULL) {
        $conn = getConnection();
        $sql = "INSERT INTO books (title,author,publisher,published,description,isbn13,isbn10,smallthumbnail,thumbnail,textsnippet,googleId) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            createErrorMsg(__FUNCTION__,"sql-error","title=".$title,"author=".$authors,"publisher=".$publisher,"publishedDate=".$publishedDate,"description=".$description,"isbn13=".$isbn13,"isbn10=".$isbn10,"smallthumbnail=".$smallthumbnail,"thumbnail=".$thumbnail,"textsnippet=".$textsnippet,"googleId=".$googleId);
            return false;
        } else {
            mysqli_stmt_bind_param($stmt, "sssssssssss", $title, $authors, $publisher, $publishedDate, $description, $isbn13, $isbn10, $smallthumbnail, $thumbnail, $textsnippet, $googleId);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }else {
        createErrorMsg(__FUNCTION__,"general-error","googleId null","title=".$title,"author=".$authors,"publisher=".$publisher,"publishedDate=".$publishedDate,"description=".$description,"isbn13=".$isbn13,"isbn10=".$isbn10,"smallthumbnail=".$smallthumbnail,"thumbnail=".$thumbnail,"textsnippet=".$textsnippet,"googleId=".$googleId);
        return false;
    }
}
function addToBooksNotInGoogle($title) {
    // Adds books to the db of books not in google
    $conn = getConnection();
    $sql = "INSERT INTO booksnotingoogle (title) VALUES (?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","title=".$title);
        return false;
    } else {
        mysqli_stmt_bind_param($stmt, "s", $title);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
function addComment($comment,$bookId) {
    // adds comment to book with bookId
    $conn = getConnection();
    $sql = "UPDATE books SET comments=? WHERE bookId=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","bookId=".$bookId,"comment=".$comment);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "ss", $comment,$bookId);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function changeUserData($array) {
    // Returns the changed userdata
    // array is all of the userinfo from getUserInfo
    $uid = $array['6'];
    $oldData = getUserInfo($uid);
    $conn = getConnection();
    $categories = getCategories('info');
    if (sizeof($categories) != sizeof($array)) {
        createErrorMsg(__FUNCTION__,"general-error","sizeof categories not matching array size","array=".$array);
        return false;
    }
    $stmt = mysqli_stmt_init($conn);
    for ($x = 0; $x < sizeof($array); $x++) {
        $var = strval($categories[strval($x)]);
        $sql = "UPDATE info SET  $var=? WHERE uid=$uid";
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            createErrorMsg(__FUNCTION__,"sql-error","array=".$array);
            return false;
        }else{
            if ($oldData[strval($x)] != $array[strval($x)]) {
                mysqli_stmt_bind_param($stmt, 's', $array[strval($x)]);
            }else {
                mysqli_stmt_bind_param($stmt, 's', $oldData[strval($x)]);
            }
            mysqli_stmt_execute($stmt);
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return getUserInfo($uid);
}

function checkBookInDbById($googleId) {
    // returns true if book is in db otherwise false
    // googleId is the id google uses the identify the books
    $conn = getConnection();
    $sql = "SELECT googleId FROM books WHERE googleId=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","googleId=".$googleId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt,"s",$googleId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultRows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        if($resultRows > 0) {
           // echo "id ".$googleId.' in library';
            return true;
        }else {
           // echo "id ".$googleId.' NOT in library';
            return false;
        }

    }
}
function checkBookInDbByTitleNAuthor($title, $author) {
    $conn = getConnection();
    $sql = "SELECT title FROM booksnotingoogle WHERE title=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        // TODO ERRORHANTERING
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $title);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultRows = mysqli_stmt_num_rows($stmt);
        if ($resultRows > 0) {
            // echo $title.$author.' In booksnotingoogle';
            return true;
        }else {
            $sql = "SELECT * FROM books WHERE title=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                // TODO ERRORHANTERING
                return false;
            } else {
                mysqli_stmt_bind_param($stmt, "s", $title);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $array = mysqli_fetch_array($result, MYSQLI_NUM);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                if (strcasecmp($array['2'], $author) == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}

function updateSqlWithRating($rating,$bookId,$removedRating = 0,$changedRating = 0){
    $conn = getConnection();
    $sql = "SELECT * FROM books WHERE bookId=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","rating=".$rating,"bookId=".$bookId,"removedRating=".$removedRating,"changedRating".$changedRating);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $bookId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);

        (float)$currentRating = $array['11'];
        (int)$nrOfRatings = $array['14'];
        if($rating == -1 && $removedRating != 0) {
            if($currentRating == NULL){
                createErrorMsg(__FUNCTION__,"general-error","can't remove rating when it's already null","rating=".$rating,"bookId=".$bookId,"removedRating=".$removedRating,"changedRating".$changedRating);
                return false;
            }else if ((int) $nrOfRatings - 1 < 0) {
                createErrorMsg(__FUNCTION__,"general-error","nr of ratings < 1 ","rating=".$rating,"bookId=".$bookId,"removedRating=".$removedRating,"changedRating".$changedRating);
                return false;
            }else if ((int) $nrOfRatings == 1 ){
                $currentRating = NULL;
                $nrOfRatings = NULL;
            }else{
                $currentRating = (((float)$currentRating*$nrOfRatings)-$removedRating)/($nrOfRatings - 1);
                $nrOfRatings--;
            }
        }else if ($rating == -2 && $removedRating != 0 && $changedRating != 0){
            if($currentRating == NULL){
                createErrorMsg(__FUNCTION__,"general-error","can't change rating when it's already null","rating=".$rating,"bookId=".$bookId,"removedRating=".$removedRating,"changedRating".$changedRating);
                return false;
            }else if($nrOfRatings == 1) {
                $currentRating = $changedRating;
            }else{
                $currentRating = ((((float)$currentRating*$nrOfRatings)-$removedRating)/($nrOfRatings - 1)+$changedRating)/2;
            }
        }else {
            if($currentRating == NULL){
                $currentRating = $rating;
                $nrOfRatings = 1;
            }else {
                (float)$currentRating = ($currentRating+$rating)/2;
                $nrOfRatings++;
            }
        }
        $sql = "UPDATE books SET rating=?, nrOfRatings=? WHERE bookId=?";
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            createErrorMsg(__FUNCTION__,"sql-error","rating=".$rating,"bookId=".$bookId,"removedRating=".$removedRating,"changedRating".$changedRating);
            return false;
        }else {
            mysqli_stmt_bind_param($stmt, "sss", $currentRating,$nrOfRatings,$bookId);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}

// BOOKLIST FUNCTIONS
function addList($listName,$userId){
    $conn = getConnection();
    $sql = "SELECT listName FROM lists WHERE uid=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","error 1","listName=".$listName,"userId".$userId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultRows = mysqli_stmt_num_rows($stmt);
        if($resultRows == 0) {
            $sql = "INSERT INTO lists (uid,listName,list) VALUES (?,?,?)";
            if(!mysqli_stmt_prepare($stmt,$sql)) {
                createErrorMsg(__FUNCTION__,"sql-error","error 2","listName=".$listName,"userId".$userId);
                return false;
            }else {
                $null = NULL;
                mysqli_stmt_bind_param($stmt, "sss", $userId,$listName,$null);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                return true;
            }
        }else if($resultRows > 0) {
            $sql = "SELECT * FROM lists WHERE uid=?";
            if(!mysqli_stmt_prepare($stmt,$sql)) {
                createErrorMsg(__FUNCTION__,"sql-error","error 3","listName=".$listName,"userId".$userId);
                return false;
            }else {
                mysqli_stmt_bind_param($stmt, "s", $userId);
                mysqli_stmt_execute($stmt);

                $array = mysqli_fetch_array(mysqli_stmt_get_result($stmt), MYSQLI_NUM);
                // listName already in list
                if (contains($listName, $array['1'])) {
                    createErrorMsg(__FUNCTION__,"general-error","ListName already in list","listName=".$listName,"userId".$userId);
                    return false;
                } else {
                    if ($array['1'] == NULL) {
                        $array['1'] = $listName;
                    } else {
                        $array['1'] = $array['1'] . ';:' . $listName;
                    }
                    $sql = "UPDATE lists SET listName=? WHERE uid=?";
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        createErrorMsg(__FUNCTION__,"sql-error","error 4","listName=".$listName,"userId".$userId);
                        return false;
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $array['1'], $userId);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        return true;
                    }
                }
            }
        }else {
            createErrorMsg(__FUNCTION__,"general-error","unknown error","listName=".$listName,"userId".$userId);
            return false;
        }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return false;
    }
}
function removeList($listName,$userId) {
    $conn = getConnection();
    $sql = "SELECT * FROM lists WHERE uid=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","listName=".$listName,"userId".$userId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        // listName already in list
        if(!contains($listName,$array['1'])) {
            createErrorMsg(__FUNCTION__,"general-error","ListName already in list","listName=".$listName,"userId".$userId);
            return false;
        }else {
            if(!contains(';:',$array['1'])) {
                $array['1'] = NULL;
            }else if(contains(';:'.$listName,$array['1'])) {
                $array['1'] = str_replace(';:'.$listName,'',$array['1']);
            }else {
                $array['1'] = str_replace($listName.';:','',$array['1']);
            }
            $sql = "UPDATE lists SET listName=? WHERE uid=?";
            if(!mysqli_stmt_prepare($stmt,$sql)) {
                createErrorMsg(__FUNCTION__,"sql-error","listName=".$listName,"userId".$userId);
                return false;
            }else {
                mysqli_stmt_bind_param($stmt, "ss", $array['1'], $userId);
                mysqli_stmt_execute($stmt);
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
function addbookToList($listName,$bookId,$userId) {
    $conn = getConnection();
    $sql = "SELECT * FROM lists WHERE uid=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        createErrorMsg(__FUNCTION__,"sql-error","error 1","listName=".$listName,"userId".$userId,"bookId=".$bookId);
        return false;
    }else {
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $array = mysqli_fetch_array($result, MYSQLI_NUM);
        $lists = $array['1'];
        $listsBook = $array['2'];

        if($lists == NULL) {
            // TODO ERROR no list
        }else {
            if(!contains(';:',$lists)) {
                if(strcasecmp($listName,$lists) == 0) {
                    if($listsBook == NULL) {
                        $listsBook = $bookId;
                    }else {
                        $listsBook =  $listsBook.'::'.$bookId;
                    }
                }else {
                    // TODO ERROR no list with listname
                }
            }else {
                $lists = explode(';:',$array['1']);
                if($key=array_search($listName,$lists) != false) {
                    $listsBook = explode(';:',$array['2']);
                    if($listsBook[strval($key)] == NULL) {
                        $listsBook[strval($key)] = $bookId;
                    }else {
                        $listsBook[strval($key)] =  $listsBook[strval($key)].'::'.$bookId;
                    }
                }else {
                    // TODO ERROR no list with listname
                }
            }
            $tmpListsBook = "";
            $tmpLists = "";
            for($x = 0;$x<sizeof($listsBook);$x++) {
                if($x != 0) {
                    $tmpListsBook = $tmpListsBook.';:'.$listsBook[strval($x)];
                    $tmpLists = $tmpLists.';:'.$lists[strval($x)];
                }else {
                    $tmpListsBook = $listsBook[strval($x)];
                    $tmpLists = $lists[strval($x)];
                }
            }
            if($tmpListsBook == "") {
                $tmpListsBook = NULL;
            }
            if($tmpLists == "") {
                $tmpListsBook = NULL;
            }
            $sql = "UPDATE lists SET listName=?,list=? WHERE uid=?";
            if(!mysqli_stmt_prepare($stmt,$sql)) {
                createErrorMsg(__FUNCTION__,"sql-error","error 2","listName=".$listName,"userId".$userId);
                return false;
            }else {
                mysqli_stmt_bind_param($stmt, "s", $tmpLists, $tmpListsBook, $userId);
                mysqli_stmt_execute($stmt);
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
function removeBookFromList($listName,$bookId,$userId) {

}

// HELPER FUNCTIONS
function contains($needle, $haystack){
    return strpos($haystack, $needle) !== false;
}
function createErrorMsg($function,$logName, ...$params) {
    $timestamp = time();
    $error = date("F d, Y h:i:s A", $timestamp).': The function '.$function.' generated a sql error with ';
    if($params != null && gettype($params) == "Array") {
        $tmp = arrayToString($params,'::');
    }else {
        $tmp = $params;
    }
    $error = $error.$tmp;
    error_log($error,3,"./tmp/logs/".$logName.".log" );
}
function arrayToString($array, $divider){
    $tmp="";
    foreach ($array as $x) {
        if(gettype($x)=="Array") {
            $tmp = $tmp.arrayToString($x,'::');
        }else {
            $tmp = $tmp.$divider.$x;
        }
    }
    return $tmp;
}




















