<?php

class viewHTML {
    
    public function showHTML($body) {

        if($body === NULL) {

            throw new \Exception("HTMLView::echoHTLM does not allow body to be null");
        }

        echo "
            <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
            <html xmlns='http://www.w3.org/1999/xhtml'>
            <head>
                <title>Workshop 2</title>
                <meta http-equiv='content-type' content='text/html; charset=utf-8' />
            </head>
            <body>
                <h1>Medlemsregister - Workshop 2</h1>
                <p> $body </p>
            </body>
            </html>";
    }
}