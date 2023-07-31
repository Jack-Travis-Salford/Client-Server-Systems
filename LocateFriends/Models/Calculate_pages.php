<?php

class Calculate_pages {

    protected $isPrevPage=false, $isNextPage=false, $totalPages;

    public function __construct($totalResults, $currentPage) { //Passed 2 integer vars

        $approxPages = $totalResults / 25; //Calculated approx amount of pages (decimal if totalResults isn't a multiple of 25

        $extra = $totalResults % 25; //Works out remainder of last page

        if($extra != 0) { //If there was a remainder
            $this->totalPages = (int) $approxPages + 1; //Remove decimal from approxPages, and add 1
        }
        else
        {
            $this->totalPages = (int) $approxPages; //Else, remove decimal from approxPages
        }
        if($currentPage > 1) {
            $this->isPrevPage = true; //If user is on page other than page 1, there is a previous page
        }
        if($currentPage<$this->totalPages) {
            $this->isNextPage = true; //If user is on a page other than the last page, there is a next page
        }
    }
    //ACCESSORS

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @return bool
     */
    public function isNextPage()
    {
        return $this->isNextPage;
    }

    /**
     * @return bool
     */
    public function isPrevPage()
    {
        return $this->isPrevPage;
    }
}