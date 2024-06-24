<style>
    .linki{
        text-decoration:none;
    }
    .complete
    {
        text-decoration:line-through;
    }
    </style>
<?php
$db  = new mysqli('localhost', "root", '','shoppinglist');

//sprawdz czy otrzymałeś dane z formularza 
if(isset($_REQUEST['newThing']) && $_REQUEST['newThing']!=""){
    //wysłano nowa rzecz do dodoania 
echo"dodaje do listy";

//tworzy kwerende 
$q = $db->prepare('INSERT INTO lista VALUES (NULL, ?,0)');
//podstawia wartosci zamiast znakow zapytania 
$q->bind_param('s',$_REQUEST['newThing']);
//'s' oznacza element tekstowy typu 'string'
//wywolaj kwerende 
$q->execute();
}

//SPRAWDZ CZY TZRYMALIŚMY kwerende do usuniecia 
if(isset($_REQUEST['removeThing'])){
    //tworzymy kwerende 
    $q = $db->prepare("DELETE FROM lista WHERE id=?");
    //podstaw id jako int stad i 
    $q->bind_param('i', $_REQUEST['removeThing']);
    $q->execute();
}

//sprawdz czy otrzymalismy pozycje do skreslenia 
if(isset($_REQUEST['completeThing'])){
    echo"skreslam pozycje";
    //tworze kwerende 
    $q=$db->prepare("UPDATE lista SET complete=1 WHERE id=?");
    //podstaw id jako int stad i n
    $q->bind_param('i', $_REQUEST['completeThing']);
    $q->execute();
}
$q = 'SELECT * FROM lista';
$result = $db->query($q);
//wyciagamy jeden wiersz z wyniku
//#row = $result->fetch_assoc();

//wyswietl pozycję z listy zakupów 
//echo $row['thing'];

echo'<ul>';
while($row = $result->fetch_assoc()){
    if($row['complete']){
        //juz kupione 
        echo '<li class="complete">';
    }
    else {
        //start list item 
        echo'<li>';
    }
    //put list item
    echo $row['produkty'];
    //wygeneruj link do wywpołania kodu usuwajacego 
    echo '<a href="index.php?removeThing='.$row['id'].'" class="linki"> X </a>';
    //wygeneruj link do wywpołania kodu skreslajacego 
    echo '<a href="index.php?completeThing='.$row['id'].'" class="linki"> &#x2714 </a>';

    //end list item 
    echo'</li>';
}
//end unordered list 
echo'</ul>';

?>
<form action = "index.php" method="get">
<label for="newThing">Dodaj do listy:</label>
<input type="text"name="newThing" id="newThing">
<input type ="submit" value="Dodaj">
</form>
<?php
//debug,testy działania na bazie danych 
echo '<pre>';
var_dump($_REQUEST);

?>