class Search{
    static sBar;
    static sResultsList;
    static currentFocus;
    static getSuggestionsRunning;
    static wantNewSuggestions;

    constructor() {
        Search.sBar = document.getElementById("searchBar");
        Search.sResultsList = document.getElementById("searchResultList");
        Search.getSuggestionsRunning = false;
        Search.wantNewSuggestions = false;
        document.getElementById("searchBar").addEventListener("input", Search.getSuggestions); //When value of searchbar changes, run getSuggestions
        document.addEventListener("click", function (e) { //Adds event listener to entire doc
            this.textContent = ""; //Removes all previous suggestions (if present)
        });

        Search.sBar.addEventListener("keydown", function(e) { //Performs actions if specific keys are pressed whilst sBar is selected
            if(e.code === "ArrowDown"){
                e.preventDefault(); //Stops action doing default (go to end of search term)
                Search.sBarArrowDownEvent(); //Function to change value of current focus and provide focus to said element as appropriate
            }
            else if(e.code === "ArrowUp") {
                e.preventDefault() //Stops action doing default (go to front of search tern)
                Search.sBarArrowUpEvent(); ///Function to change value of current focus and provide focus to said element as appropriate
            }
            else if(e.code === "Enter"){
                if(currentFocus > 0){ //If user has navigated at least 1 element into list
                    e.preventDefault(); //Prevent default action (form submit)
                    document.getElementById("match_"+currentFocus).click(); //Click div to trigger event listener
                }
            }

        });
    }
    static getSuggestions(){
        if (!Search.getSuggestionsRunning) {
            Search.getSuggestionsRunning = true;
            let val = Search.sBar.value;
            Search.sResultsList.textContent = ""; //Removes all previous suggestions (if present)
            if (val.trim() === "") { //If val is empty/just whitespace
                if (Search.wantNewSuggestions) {
                    Search.getSuggestionsRunning = false;
                    Search.wantNewSuggestions = false;
                    Search.getSuggestions()
                } else {
                    Search.getSuggestionsRunning = false;
                }
                return null; //Dont do anything
            } else { //Else, proceed with fetch
                fetch('search_Bar_Suggestions.php?searchTerm=' + Search.sBar.value).then(function (response) //Fetch upto first 10 matches, then...
                {
                    return response.text(); //Return text to function
                }).then(function (data) {
                    data = JSON.parse(data);
                    Search.currentFocus = 0; //Sets focus to unachievable number (denotes nothing selected)
                    let matchingResult;
                    if (data.length === 0) { //If there are no matches
                        matchingResult = document.createElement("div"); //Creates div
                        matchingResult.innerHTML = "<p class='suggestion_text'>There are no matches to your search</p>"; //Adds text to div
                        Search.sResultsList.appendChild(matchingResult); //Adds div a child to results list
                    } else {
                        for (let x = 0; x < data.length; x++) { //For all returned matches (max of 10)
                            matchingResult = document.createElement("div"); //Create div to hold result
                            matchingResult.id = "match_" + (x + 1); //Sets id of div for later referencing
                            let img = "images/" + data[x].userID + ".png"; //Holds location of where users image would be
                            if (Search.imageExists(img) === true) { //Calls function to see if image exists. If it does, do...
                                matchingResult.innerHTML = "<img class='suggestion_img' src='"+img+"' width='50' height='50' alt='userImage.png'>"; //Add image to b
                            } else { //If it doesn't exist, do...
                                matchingResult.innerHTML = "<img  class='suggestion_img' src='images/Img-Placeholder.png' width='50' height='50' alt='userImage.png'>"; //Add placeholder image to b
                            }
                            matchingResult.innerHTML += "<p class='suggestion_text'>" + data[x].username + "<br>" + data[x].first_name + " " + data[x].surname + "</p>";
                            matchingResult.addEventListener("click", function (e) { //When user clicks result div, do...
                                window.location.href = "user.php?selectedID=" + data[x].userID; //Direct to users info page
                            });
                            Search.sResultsList.appendChild(matchingResult);
                        }
                    }
                    if (Search.wantNewSuggestions) {
                        Search.getSuggestionsRunning = false;
                        Search.wantNewSuggestions = false;
                        Search.getSuggestions()
                    } else {
                        Search.getSuggestionsRunning = false;
                    }
                }).catch(function (error) {
                    console.log('Error: ' + error);
                });

            }

        } else {
            this.wantNewSuggestions = true;
        }
    }
    static imageExists(image_url) { //Passed image.
        var http = new XMLHttpRequest();
        http.open('HEAD', image_url, false); //Attempts to get image.
        http.send();
        return http.status !== 404; //If image found, return true. Else, return false
    }
    static sBarArrowDownEvent() { //Controls what happens when arrow down is pressed when sBar is selected
        let currentMatches = Search.sResultsList.childElementCount; //Get total current displayed matches
        if(currentMatches === 0){ //If sBar is empty
            return false; //do nothing
        }
        else {
            if (Search.currentFocus>0){ //If a search result element has focus
                document.getElementById("match_"+Search.currentFocus).className = ""; //Remove "active" tab
            }

            if (Search.currentFocus< currentMatches) { //if current focus is not on last match
                Search.currentFocus++; //Increment currentFocus
            }
            else{ //If current focus was on last child
                Search.currentFocus = 1; //Set currentFocus back to first result
            }
        }
        document.getElementById("match_"+Search.currentFocus).className = "searchResults-active";

    }
    static sBarArrowUpEvent() { //Controls what happens when arrow down is pressed when sBar is selected
        let currentMatches = Search.sResultsList.childElementCount; //Get total current displayed matches
        if(currentMatches === 0){ //If sBar is empty
            return false; //do nothing
        }
        else {
            if (Search.currentFocus>0){ //If a search result element has focus
                document.getElementById("match_"+Search.currentFocus).className = ""; //Remove "active" tab
            }
            if (Search.currentFocus <= 1) { //if current focus is on first item
                Search.currentFocus = currentMatches; //Increment currentFocus
            }
            else{ //If current focus was on last child
                Search.currentFocus --; //Set currentFocus back to first result
            }
        }
        document.getElementById("match_"+Search.currentFocus).className = "searchResults-active";

    }
}
new Search();











