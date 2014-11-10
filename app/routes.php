<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

use Paste\Pre;


Route::get('/', function() {
	return View::make('hello');
});

Route::get('/books/{id}', function($id) {
    $jsnContent = File::get(app_path().'/database/json.example.json');
    echo Pre::render($jsnContent,'jsnContent').'<br>';
    
    return View::make('title')->with('item',$id);
});

Route::get('/books/{category}', function($category) {
        return 'Here are all the books in the category of '.$category;
}); 


Route::get('/new', function() {
    $view  = '<form method="POST">';
    $view .= 'Title: <input type="text" name="title">';
    $view .= '<br><br>';
    $view .= '<input type="submit">';
    $view .= '</form>';

    return $view;
});

Route::post('/new', function() {
    $input =  Input::all();
    print_r($input);
});

Route::get('/practice', function() {
    echo App::environment();
});

Route::get('/mysql-test', function() {

    # Print environment
    echo 'Environment: '.App::environment().'<br>';

    # Use the DB component to select all the databases
    $results = DB::select('SHOW DATABASES;');

    # If the "Pre" package is not installed, you should output using print_r instead
    echo Pre::render($results);

});

Route::get('/get-environment',function() {

    echo "Environment: ".App::environment();

});

Route::get('/debug', function() {

    echo '<pre>';

    echo '<h1>environment.php</h1>';
    $path   = base_path().'/environment.php';

    try {
        $contents = 'Contents: '.File::getRequire($path);
        $exists = 'Yes';
    }
    catch (Exception $e) {
        $exists = 'No. Defaulting to `production`';
        $contents = '';
    }

    echo "Checking for: ".$path.'<br>';
    echo 'Exists: '.$exists.'<br>';
    echo $contents;
    echo '<br>';

    echo '<h1>Environment</h1>';
    echo App::environment().'</h1>';

    echo '<h1>Debugging?</h1>';
    if(Config::get('app.debug')) echo "Yes"; else echo "No";

    echo '<h1>Database Config</h1>';
    print_r(Config::get('database.connections.mysql'));

    echo '<h1>Test Database Connection</h1>';
    try {
        $results = DB::select('SHOW DATABASES;');
        echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
        echo "<br><br>Your Databases:<br><br>";
        print_r($results);
    } 
    catch (Exception $e) {
        echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    }

    echo '</pre>';

});

Route::get('/practice-creating', function() {

    # Instantiate a new Book model class
    $booke = new Book();
   #  print_r($book);
   #  echo "<br>";
   #  echo "<br>";
    
    # Set 
    $booke->title = 'The Great Gatsby';
    $booke->author = 'F. Scott Fiztgerald';
    $booke->published = 1925;
    $booke->cover = 'http://img2.imagesbn.com/p/9780743273565_p0_v4_s114x166.JPG';
    $booke->purchase_link = 'http://www.barnesandnoble.com/w/the-great-gatsby-francis-scott-fitzgerald/1116668135?ean=9780743273565';
    $booke->page_count=15;

    # This is where the Eloquent ORM magic happens
    $booke->save();
    
        echo "hey ";
    print_r($booke);

    return 'A new book has been added! Check your database to see...13';

});

Route::get('/hey', function()
{
    $game = new Game;
    $game->name = 'Assassins Creed';
    $game->description = 'Assassins VS templars.';
    $game->save();
});

Route::get('/practice-reading', function() {

    # The all() method will fetch all the rows from a Model/table
    $books = Book::all();

    # Make sure we have results before trying to print them...
    if($books->isEmpty() != TRUE) {

        # Typically we'd pass $books to a View, but for quick and dirty demonstration, let's just output here...
        foreach($books as $book) {
            echo $book->title.'<br>';
        }
    }
    else {
        return 'No books found';
    }

});

Route::get('/practice-updating', function() {

    # First get a book to update
    $book = Book::where('author', 'LIKE', '%Scott%')->first();

    # If we found the book, update it
    if($book) {

        # Give it a different title
        $book->title = 'The TJB Great Gatsby';

        # Save the changes
        $book->save();

        return "Update complete; check the database to see if your update worked...";
    }
    else {
        return "Book not found, can't update.";
    }

});

//////////////////////////////////////////////////////////////////////////////
Route::get('/blog-author', function() {

    # Instantiate a new Author model class
    $author = new Author();
    
    # Set 
    $author->name = 'Bill User1';
    $author->save();
    
    # Instantiate a new Topic model class
    $topic = new Topic;
    $topic->topic_name = 'This is Topic 3';
    $topic->topic_content = 'This is a topic for a blog.  Let\'s talk';
    $topic->author()->associate($author); # <--- Associate the author with this Topic
    $topic->save();    
    
    #echo "TOPIC ";
    #print_r($topic);
    
    # Instantiate a new Reply model class
    $reply = new Reply;
    $reply->content = 'This is a reply to Topic 3';
    $reply->author()->associate($author); # <--- Associate the author with this Reply
    $reply->save();
    $reply->topics()->attach($topic);
    
    # Instantiate a new Comment model class
    $comment = new Comment;
    $comment->content = 'This is a comment to Reply 2';
    $comment->author()->associate($author); # <--- Associate the author with this Comment
    $comment->save();
    $comment->replies()->attach($reply);    
    
    return 'A new author, topic and reply and comment have been created! ';
});

Route::get('/blog-fetch', function() {
    $reply = Reply::first();
    echo $reply->author->name . " wrote the following " . $reply->content;

		

});


Route::get('/signup',
    array(
        'before' => 'guest',
        function() {
            return View::make('signup');
        }
    )
);

///////////login and authentication code here///////////////////////
Route::post('/signup', 
    array(
        'before' => 'csrf', 
        function() {

            $user = new User;
            $user->email    = Input::get('email');
            $user->password = Hash::make(Input::get('password'));

            # Try to add the user 
            try {
                $user->save();
            }
            # Fail
            catch (Exception $e) {
                return Redirect::to('/signup')->with('flash_message', 'Sign up failed; please try again.')->withInput();
            }

            # Log the user in
            Auth::login($user);

            return Redirect::to('/list')->with('flash_message', 'Welcome to Foobooks!');

        }
    )
);

Route::get('/login',
    array(
        'before' => 'guest',
        function() {
            return View::make('login');
        }
    )
);

Route::post('/login', 
    array(
        'before' => 'csrf', 
        function() {

            $credentials = Input::only('email', 'password');

            if (Auth::attempt($credentials, $remember = true)) {
                return Redirect::intended('/debug')->with('flash_message', 'Welcome Back!');
            }
            else {
                return Redirect::to('/login')->with('flash_message', 'Log in failed; please try again.');
            }

            return Redirect::to('/debug');
        }
    )
);

Route::get('/list/{format?}', 
    array(
        'before' => 'auth', 
        function($format = 'html') {
            echo "I think this is the right answer.";
        }
    )
);


Route::get('/logout', function() {

    # Log out
    Auth::logout();
    
    echo "you are loggd out";

    # Send them to the homepage
    return Redirect::to('/hey');

});






