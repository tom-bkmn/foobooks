<?php

class Comment extends Eloquent {

    public function author() {
        # Comment belongs to Author
        # Define an inverse one-to-many relationship.
        return $this->belongsTo('Author');
    }

    public function replies() {
        # Comments belong to many Replies     
        return $this->belongsToMany('Reply');
    }

}			