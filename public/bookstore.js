/**
 * Created by garbi on 18.04.16.
 */

function loadBook() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            displayCD(xhttp);
        }
    };
    xhttp.open("GET", "http://0.0.0.0:8080/books/", true);
    xhttp.send();
}

function deleteBook(bookId) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "http://0.0.0.0:8080/deleteBook/"+bookId, true);
    xhttp.send();
}


function displayCD(xhttp)
{

// Add your code here -----------------------------------------

    var jsonObj = JSON.parse(xhttp.responseText);
    var books = jsonObj.books;

    var tableBody = document.getElementById("myBooks").getElementsByTagName('tbody')[0];

    for (i=0; i<books.length; i++){

        id = books[i].id;
        title = books[i].title;
        author = books[i].author;
        isbn = books[i].isbn;

        var row = tableBody.insertRow(tableBody.rows.length); //can start at the beginning, argument = 0

        row.insertCell(0).innerHTML = id;
        row.insertCell(1).innerHTML = title;
        row.insertCell(2).innerHTML = author;
        row.insertCell(3).innerHTML = isbn;
        row.insertCell(4).innerHTML = '<a href="showBooks.html" onclick="deleteBook('+id+')">Delete</a>';
    }
}

function addBook()
{

    title = document.getElementById('titleInput');
    author = document.getElementById('authorInput');
    isbn = document.getElementById('isbnInput');

    var book = {
                title:title.value,
                author:author.value,
                isbn:isbn.value
    };
    var bookString = JSON.stringify(book);

    console.log(bookString);

    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "http://0.0.0.0:8080/addBook/", true);
    xhttp.send(bookString);

}
// -------------------------------------------------------------
