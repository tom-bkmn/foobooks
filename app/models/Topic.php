<?php

class Topic extends Eloquent {

    public function author() {
        # Topic belongs to Author
        # Define an inverse one-to-many relationship.
        return $this->belongsTo('Author');
    }

    public function replies() {
        # Topics belong to many Replies
        return $this->belongsToMany('Reply');
    }

}			