/*
Internet Technologies SS2016 - Remote Systems

With this exercise we will learn how to use a simple set of Web services to read and write data to a simple Wed data store

*/

function loadBook() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            displayCD(xhttp);
        }
    };
    xhttp.open("GET", "books.json", true);
    xhttp.send();
}

// Your Code from last week
function displayCD(xhttp)
{
    var jsonObj = JSON.parse(xhttp.responseText);
    var books = jsonObj.books;
    var tableBody = document.getElementById("myBooks").getElementsByTagName('tbody')[0];

    for (i=0; i<books.length; i++){

        title = books[i].title;
        author = books[i].author;
        isbn = books[i].isbn;

        var row = tableBody.insertRow(tableBody.rows.length); //can start at the beginning, argument = 0

        row.insertCell(0).innerHTML = title;
        row.insertCell(1).innerHTML = author;
        row.insertCell(2).innerHTML = isbn;
    }
}
// ---------------------------------------


function deleteBook(){

}

function addBook()
{



}
// -------------------------------------------------------------
