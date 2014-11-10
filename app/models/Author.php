<?php

class Author extends Eloquent {

    public function topic() {
        # Author has many Topics
        # Define a one-to-many relationship.
        return $this->hasMany('Topic');
    }

    public function reply() {
        # Author has many Replies
        # Define a one-to-many relationship.
       return $this->hasMany('Reply');
    }

    public function comment() {
        # Author has many Comments
        # Define a one-to-many relationship.
        return $this->hasMany('Comment');
    }
}			