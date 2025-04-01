// This file can be injected into the page context for direct DOM manipulation
// Unlike content.js, this script runs in the same JavaScript context as the webpage

(function() {
  // This function can be used to help with posting replies or other actions
  // that might be difficult from the isolated content script context
  
  window.supaPostHelpers = {
    // Helper method to fill in the reply box with the generated text
    fillReplyBox: function(text) {
      try {
        // Find the reply textarea
        const replyTextarea = document.querySelector('[data-testid="tweetTextarea_0"]');
        if (!replyTextarea) return false;
        
        // Focus the textarea
        replyTextarea.focus();
        
        // Set its content
        replyTextarea.textContent = text;
        
        // Dispatch input event to trigger X.com's internal state updates
        const inputEvent = new Event('input', { bubbles: true });
        replyTextarea.dispatchEvent(inputEvent);
        
        return true;
      } catch (error) {
        console.error('Error filling reply box:', error);
        return false;
      }
    },
    
    // Helper method to click the reply button on a tweet
    clickReplyButton: function() {
      try {
        const replyButton = document.querySelector('[data-testid="reply"]');
        if (!replyButton) return false;
        
        // Find the clickable container
        let clickableElement = replyButton;
        while (clickableElement && !clickableElement.getAttribute('role') === 'button') {
          clickableElement = clickableElement.parentElement;
        }
        
        if (!clickableElement) return false;
        
        // Click the reply button
        clickableElement.click();
        return true;
      } catch (error) {
        console.error('Error clicking reply button:', error);
        return false;
      }
    },
    
    // Helper method to submit the reply by clicking the tweet button
    submitReply: function() {
      try {
        const tweetButton = document.querySelector('[data-testid="tweetButton"]');
        if (!tweetButton) return false;
        
        // Click the tweet button
        tweetButton.click();
        return true;
      } catch (error) {
        console.error('Error submitting reply:', error);
        return false;
      }
    },
    
    // Helper method to post a reply in one step
    postReply: function(text) {
      // First click the reply button
      if (!this.clickReplyButton()) return false;
      
      // Wait for the reply dialog to appear
      setTimeout(() => {
        // Fill in the reply text
        if (this.fillReplyBox(text)) {
          // Then submit the reply
          setTimeout(() => {
            this.submitReply();
          }, 500);
        }
      }, 500);
      
      return true;
    }
  };
  
  // Notify the content script that the inject script has loaded
  window.dispatchEvent(new CustomEvent('supapost_injected'));
})(); 