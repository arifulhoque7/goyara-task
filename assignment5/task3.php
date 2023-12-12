<?php
class Book {
    private $title;
    private $author;
    private $publicationYear;

    public function __construct($title, $author, $publicationYear) {
        $this->title = $title;
        $this->author = $author;
        $this->publicationYear = $publicationYear;
    }

    public function getDetails() {
        return "{$this->title} by {$this->author} ({$this->publicationYear})";
    }
}

// Example usage:
$book = new Book("The Great Gatsby", "F. Scott Fitzgerald", 1925);
echo $book->getDetails(); // Output: "The Great Gatsby by F. Scott Fitzgerald (1925)"
?>