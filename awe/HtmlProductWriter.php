<?php

namespace awe;

class HtmlProductWriter extends ShopProductWriter
{

    public function write()
    {
        echo '<!DOCTYPE html>' . "\n";
        echo '<html lang="en">';

        echo $this->htmlHeader();
        echo $this->htmlBody();

        echo '</html>';
    }

    private function htmlHeader()
    {
        return
            '<head>
                <title>AWE Product List</title>
                <link rel="stylesheet" href="./css/styles.css">
            </head>';
    }

    private function htmlBody()
    {
        $bookproducts = [];
        $cdproducts = [];

        foreach ($this->products as $product) {
            if($product instanceof BookProduct) $bookproducts[] = $product;
            if($product instanceof CdProduct) $cdproducts[] = $product;
        }

        $booktable = $this->generateBookTable($bookproducts);
        $cdtable = $this->generateCdTable($cdproducts);

        $addProduct = $this->generateAddProductForm();

        return
            '<body>'
                .$booktable .
                '<br>'
                .$cdtable.
                '<br>'
                .$addProduct .
            '</body>';
    }

    private function generateBookTable(array $bookproducts): string
    {
        $contents = '';
        foreach ($bookproducts as $book) {
            $contents .= '<tr>
                  <td>' . htmlspecialchars($book->getFullName(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td>' . htmlspecialchars($book->getTitle(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td>' . htmlspecialchars((string) $book->getNumberOfPages(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td>' . htmlspecialchars((string) $book->getPrice(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td><a href="./index.php?delete=' . rawurlencode((string) $book->getId()) . '">X</a></td>
                </tr>';
        }
        return
            '
            <h3>BOOKS</h3>
            <table class="pale-blue-rows equal-width">
                <thead>
                    <tr>
                        <th scope="col">AUTHOR</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">PAGES</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">DELETE</th>
                    </tr>
                    </thead>
                    <tbody>'
            .$contents.
                '</tbody>
            </table>';
    }

    private function generateCdTable(array $cdproducts): string
    {
        $contents = '';
        foreach ($cdproducts as $cd) {
            $contents .= '<tr>
                  <td>' . htmlspecialchars($cd->getFullName(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td>' . htmlspecialchars($cd->getTitle(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td>' . htmlspecialchars((string) $cd->getPlayLength(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td>' . htmlspecialchars((string) $cd->getPrice(), ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td><a href="./index.php?delete=' . rawurlencode((string) $cd->getId()) . '">X</a></td>
                </tr>';
        }
        return
            '
            <h3>CDs</h3>
            <table class="pale-blue-rows equal-width">
                 <thead>
                    <tr>
                        <th scope="col">ARTIST</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">DURATION</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">DELETE</th>
                    </tr>
                    </thead>
                    <tbody>'
            .$contents.
            '</tbody>
            </table>';
    }

    private function generateAddProductForm()
    {
        return '<hr>
          <h2>ADD NEW PRODUCT</h2>
         <form action="./index.php" method="post">
          <label for="producttype">Product Type:</label>
          <select id="producttype" name="producttype">
                <option value="cd">CD</option>
                <option value="book">Book</option>
          </select>
          <br>
          <br>
         <label for="name">Author / Artist:</label><br>
         <label for="fname">First Name:</label>
           <input type="text" id="fname" name="fname"><br>
          <label for="sname">Main Name / Surname:</label>
           <input type="text" id="sname" name="sname">
           <br>
           <br>
         <label for="title">Title:</label>
           <input type="text" id="title" name="title">
           <br>
           <br>
         <label for="pages">Pages/Duration:</label>
           <input type="text" id="pages" name="pages">
           <br>
           <br>
          <label for="price">Price:</label>
           <input type="text" id="price" name="price">
           <br>
           <br>
           <button type="submit">Submit</button>
        </form>';
    }
}