window.sidebarLinkDemo = function(event){
    event.preventDefault(); 
    new FilamentNotification().title('Modify this button inside of sidebar.blade.php').send();
}