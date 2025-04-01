// SupaPost for X content script
(function() {
  // Configuration
  const TWEET_CONTAINER_SELECTOR = 'article[data-testid="tweet"]';
  const TWEET_ACTIONS_SELECTOR = '[role="group"]';
  const TWEET_TEXT_SELECTOR = '[data-testid="tweetText"]';
  const TWEET_USERNAME_SELECTOR = '[data-testid="User-Name"]';
  const CHARACTER_LIMIT = 280;
  
  // X.com reply modal selectors - simpler selectors to prevent excessive matching
  const REPLY_MODAL_SELECTOR = '[aria-modal="true"][role="dialog"]';
  const REPLY_BUTTON_SELECTOR = '[data-testid="tweetButton"]';
  const REPLY_TOOLBAR_SELECTOR = 'div[role="group"]';
  const REPLY_TEXTAREA_SELECTOR = '[data-testid="tweetTextarea_0"]';
  
  // Anti-loop protection flags
  let isScanning = false;
  let processingMutation = false;
  let lastScanTime = 0;
  
  // Testing mode - generate mock replies without API
  const LOCAL_TESTING_MODE = true; // Temporarily enable for debugging
  
  // Debug mode for detailed logging
  const DEBUG_MODE = true;
  
  // Log configuration
  console.log('SupaPost X content script initialized');
  console.log('Testing mode:', LOCAL_TESTING_MODE);
  console.log('Debug mode:', DEBUG_MODE);
  
  // Sample replies for testing without API
  const SAMPLE_REPLIES = [
    "Thank you for sharing your thoughts! I appreciate your perspective on this topic.",
    "Interesting point! I'd like to add that there are multiple angles to consider here.",
    "Great observation! This is definitely something worth discussing further.",
    "I see your point, though I think there's also value in considering alternative viewpoints.",
    "Thanks for bringing this up! It's an important conversation to have."
  ];
  
  // SVG icon for the button
  const BUTTON_ICON = `
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
      <path d="M2.5 18.5C2.5 20.43 4.07 22 6 22h4c1.93 0 3.5-1.57 3.5-3.5V10c0-1.93-1.57-3.5-3.5-3.5H6c-1.93 0-3.5 1.57-3.5 3.5v8.5zm0-8.5c0-1.93 1.57-3.5 3.5-3.5h4c1.93 0 3.5 1.57 3.5 3.5v8.5c0 1.93-1.57 3.5-3.5 3.5H6c-1.93 0-3.5-1.57-3.5-3.5V10zm10.5-6.5c0 .83.67 1.5 1.5 1.5h5.5v5.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V3.5C23 2.12 21.88 1 20.5 1H14.5c-.83 0-1.5.67-1.5 1.5z"/>
    </svg>
  `;
  
  // State variables
  let settings = {};
  let modalOpen = false;
  let lastInjectedTweets = new Set();
  let workspaceSettings = {};
  
  // Init console message 
  console.log('SupaPost X content script initialized - using testing mode');
  
  // Initialize when the DOM is fully loaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeExtension);
  } else {
    initializeExtension();
  }
  
  // Also run when the page URL changes (SPA navigation)
  let lastUrl = location.href;
  new MutationObserver(() => {
    if (lastUrl !== location.href) {
      lastUrl = location.href;
      console.log('URL changed, re-initializing extension');
      initializeExtension();
    }
  }).observe(document, {subtree: true, childList: true});
  
  // Main initialization function
  function initializeExtension() {
    console.log('Initializing SupaPost extension');
    
    // First, check API key
    checkApiKeyAndInject();
    
    // Add styles immediately
    addSupaPostStyles();
    
    // Stagger operations to reduce CPU impact
    setTimeout(() => {
      // Start observing DOM changes
      setupMutationObserver();
      
      // Do an initial scan after a short delay
      setTimeout(() => {
        if (!isScanning) {
          scanForReplyModals();
        }
        
        // Start polling only after the initial scan
        setTimeout(() => {
          startModalPolling();
        }, 1000);
      }, 1000);
    }, 500);
  }
  
  // Set up mutation observer to detect DOM changes
  function setupMutationObserver() {
    // Stop any existing observer
    if (window.supapostObserver) {
      window.supapostObserver.disconnect();
    }
    
    // Create and start a new observer with more focused configuration
    window.supapostObserver = new MutationObserver(debounce(handlePageChanges, 500));
    window.supapostObserver.observe(document.body, { 
      childList: true, 
      subtree: true,
      // Don't observe attributes to reduce noise
      attributes: false
    });
    
    console.log('Mutation observer started with focused configuration');
  }
  
  // Start polling for modals periodically with a reasonable interval
  function startModalPolling() {
    // Clear any existing interval
    if (window.supapostPollInterval) {
      clearInterval(window.supapostPollInterval);
    }
    
    // Set up a new polling interval with longer delay
    window.supapostPollInterval = setInterval(() => {
      if (window.location.hostname.includes("twitter.com") || 
          window.location.hostname.includes("x.com")) {
        // Only poll for modals if we're not already scanning
        if (!isScanning) {
          console.log('Polling for reply modals');
          scanForReplyModals();
        }
      }
    }, 5000); // 5 seconds between polls to reduce CPU impact
    
    console.log('Modal polling started with longer interval');
  }
  
  // Listen for messages from background script
  chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    // Handle any messages from popup or background script
    if (request.action === "api_key_changed") {
      checkApiKeyAndInject();
    }
    sendResponse({ received: true });
  });
  
  // Check if the user has set an API key and inject buttons
  async function checkApiKeyAndInject() {
    try {
      // In local testing mode, always inject buttons
      if (LOCAL_TESTING_MODE) {
        console.log('Local testing mode enabled, watching for reply modals');
        // We no longer inject buttons directly into tweets, just watch for reply modals
        return;
      }
      
      // Check API key status
      const apiKeyStatus = await chrome.runtime.sendMessage({
        action: "check_api_key"
      });
      
      if (apiKeyStatus && apiKeyStatus.valid) {
        console.log('API key valid, watching for reply modals');
        
        // Load workspace settings if available
        if (apiKeyStatus.workspace) {
          workspaceSettings = apiKeyStatus.workspace;
          console.log('Loaded workspace settings:', workspaceSettings);
        }
        
        // Get local settings
        const storedSettings = await chrome.storage.local.get('settings');
        settings = storedSettings.settings || {};
      } else {
        console.log('API key not valid or not set');
      }
    } catch (error) {
      console.error("Error checking API key:", error);
    }
  }
  
  // Handle page changes by checking for reply modals
  function handlePageChanges(mutations) {
    // Prevent concurrent processing
    if (processingMutation) {
      return;
    }
    
    try {
      processingMutation = true;
      
      if (window.location.hostname.includes("twitter.com") || 
          window.location.hostname.includes("x.com")) {
        
        // Check if any mutation looks like it could be a modal being added
        const relevantMutation = mutations.some(mutation => {
          if (mutation.type !== 'childList' || mutation.addedNodes.length === 0) {
            return false;
          }
          
          // Look for nodes that seem like they could be modals or contain modals
          for (let i = 0; i < mutation.addedNodes.length; i++) {
            const node = mutation.addedNodes[i];
            if (node.nodeType !== Node.ELEMENT_NODE) {
              continue;
            }
            
            // Quick check - does this look like a dialog or have dialog-like attributes?
            if (node.nodeName === 'DIV' && 
                (node.getAttribute('role') === 'dialog' || 
                 node.querySelector && node.querySelector('[role="dialog"]'))) {
              return true;
            }
          }
          
          return false;
        });
        
        if (relevantMutation) {
          console.log('Detected relevant DOM changes, scheduling modal scan');
          // Schedule scan with a delay to allow DOM to settle and reduce CPU impact
          setTimeout(() => {
            if (!isScanning) {
              scanForReplyModals();
            }
          }, 500);
        }
      }
    } finally {
      // Always release the processing lock
      processingMutation = false;
    }
  }
  
  // Inject the SupaPost button into the reply modal
  function injectButtonIntoReplyModal(modal) {
    // Safety check
    if (!modal || !modal.isConnected) {
      console.log('Modal is not valid or not connected to DOM');
      return;
    }
    
    // Check if we've already injected into this modal
    if (modal.querySelector('.sp-reply-button')) {
      console.log('Button already exists in this modal, skipping injection');
      return;
    }
    
    // Find the reply button (tweet button) in the modal
    const replyButton = modal.querySelector(REPLY_BUTTON_SELECTOR);
    if (!replyButton) {
      console.log('Reply button not found with selector:', REPLY_BUTTON_SELECTOR);
      return;
    }
    
    console.log('Found reply button, injecting SupaPost button');
    
    // Get button container
    let buttonContainer = replyButton.parentElement;
    if (!buttonContainer) {
      console.log('No parent element found for reply button');
      return;
    }
    
    // Find the tweet text for later use
    const tweetTextElement = modal.querySelector(REPLY_TEXTAREA_SELECTOR);
    const username = modal.querySelector('div[dir="ltr"] span');
    
    // Extract any information we need from the modal
    const tweetData = {
      modalElement: modal,
      tweetText: tweetTextElement ? tweetTextElement.textContent : '',
      username: username ? username.textContent : '',
      url: window.location.href
    };
    
    // Create our button
    const supapostButton = document.createElement('button');
    supapostButton.className = 'sp-reply-button';
    supapostButton.innerHTML = `${BUTTON_ICON} <span>SupaPost</span>`;
    supapostButton.title = "Generate reply with SupaPost AI";
    
    // Style the button to match Twitter's UI
    supapostButton.style.cssText = `
      display: flex;
      align-items: center;
      background-color: #1d9bf0;
      color: white;
      border: none;
      border-radius: 9999px;
      padding: 0 16px;
      font-weight: bold;
      height: 36px;
      margin-right: 12px;
      cursor: pointer;
    `;
    
    // Add click event listener
    supapostButton.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      
      // If we have a tweet text element, grab the latest content
      if (tweetTextElement) {
        tweetData.tweetText = tweetTextElement.textContent;
      }
      
      // Open our modal
      openReplyModal(tweetData);
    });
    
    // Try to insert before the reply button
    try {
      buttonContainer.insertBefore(supapostButton, replyButton);
      console.log('Successfully injected SupaPost button into reply modal');
    } catch (error) {
      console.error('Error injecting button:', error);
    }
  }
  
  // Open the reply modal
  function openReplyModal(tweetData) {
    if (modalOpen) return;
    modalOpen = true;
    
    // Get tweet information
    const tweetText = tweetData.tweetText || '';
    const username = tweetData.username || '';
    const tweetUrl = tweetData.url || window.location.href;
    
    console.log('Opening modal for tweet:', { text: tweetText, username, url: tweetUrl });
    
    // Create modal HTML
    const modalHTML = `
      <div class="sp-modal-overlay">
        <div class="sp-modal">
          <div class="sp-modal-header">
            <div class="sp-modal-title">Reply with SupaPost</div>
            <button class="sp-modal-close">&times;</button>
          </div>
          <div class="sp-modal-body">
            <div class="sp-form-group">
              <label for="sp-tone">Reply Tone</label>
              <select id="sp-tone">
                <option value="casual">Casual</option>
                <option value="formal">Formal</option>
                <option value="humorous">Humorous</option>
                <option value="professional">Professional</option>
              </select>
            </div>
            
            <div class="sp-checkbox-group">
              <label class="sp-checkbox-label">
                <input type="checkbox" id="sp-longform"> Longform
              </label>
              <label class="sp-checkbox-label">
                <input type="checkbox" id="sp-emoji"> Emoji
              </label>
              <label class="sp-checkbox-label">
                <input type="checkbox" id="sp-hashtags"> Hashtags
              </label>
            </div>
            
            <div id="sp-loading" class="sp-loading hidden">
              <div class="sp-spinner"></div>
              <p>Generating reply...</p>
            </div>
            
            <div id="sp-error" class="sp-error hidden"></div>
            
            <div id="sp-reply-container">
              <textarea id="sp-reply" class="sp-textarea" placeholder="Your reply will appear here..."></textarea>
              <div class="sp-character-count">
                <span id="sp-character-count">0</span>/${CHARACTER_LIMIT}
              </div>
            </div>
          </div>
          <div class="sp-modal-footer">
            <div class="sp-modal-footer-right">
              <button id="sp-generate-btn" class="sp-btn">Generate Reply</button>
              <button id="sp-post-btn" class="sp-btn" disabled>Post Reply</button>
            </div>
          </div>
        </div>
      </div>
    `;
    
    // Create and append the modal
    const modalContainer = document.createElement('div');
    modalContainer.innerHTML = modalHTML;
    document.body.appendChild(modalContainer);
    
    // Apply saved settings
    if (settings) {
      document.getElementById('sp-tone').value = settings.defaultTone || 'casual';
      document.getElementById('sp-longform').checked = settings.longform || false;
      document.getElementById('sp-emoji').checked = settings.emoji || false;
      document.getElementById('sp-hashtags').checked = settings.hashtags || false;
    }
    
    // Add event listeners
    document.querySelector('.sp-modal-close').addEventListener('click', closeModal);
    document.getElementById('sp-generate-btn').addEventListener('click', () => generateReply(tweetText, username, tweetUrl));
    document.getElementById('sp-post-btn').addEventListener('click', () => postReply(tweetData));
    
    // Character count
    const replyTextarea = document.getElementById('sp-reply');
    const characterCount = document.getElementById('sp-character-count');
    
    replyTextarea.addEventListener('input', () => {
      const count = replyTextarea.value.length;
      characterCount.textContent = count;
      
      if (count > CHARACTER_LIMIT) {
        characterCount.classList.add('sp-warning');
      } else {
        characterCount.classList.remove('sp-warning');
      }
      
      // Enable/disable post button
      const postBtn = document.getElementById('sp-post-btn');
      postBtn.disabled = count === 0 || count > CHARACTER_LIMIT;
    });
  }
  
  // Close the modal
  function closeModal() {
    const modalOverlay = document.querySelector('.sp-modal-overlay');
    if (modalOverlay) {
      modalOverlay.remove();
    }
    modalOpen = false;
  }
  
  // Generate a reply using the SupaPost API
  async function generateReply(originalPost, username, tweetUrl) {
    try {
      // Show loading state
      const loadingElement = document.getElementById('sp-loading');
      const errorElement = document.getElementById('sp-error');
      const replyContainer = document.getElementById('sp-reply-container');
      const generateBtn = document.getElementById('sp-generate-btn');
      
      loadingElement.classList.remove('hidden');
      errorElement.classList.add('hidden');
      replyContainer.style.opacity = '0.5';
      generateBtn.disabled = true;
      
      // Get form values
      const tone = document.getElementById('sp-tone').value;
      const longform = document.getElementById('sp-longform').checked;
      const emoji = document.getElementById('sp-emoji').checked;
      const hashtags = document.getElementById('sp-hashtags').checked;
      
      console.log('Generating reply with settings:', { tone, longform, emoji, hashtags });
      
      // In local testing mode, use sample replies instead of API
      if (LOCAL_TESTING_MODE) {
        console.log('Using local testing mode for reply generation');
        
        // Simulate network delay
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        const replyIndex = Math.floor(Math.random() * SAMPLE_REPLIES.length);
        const sampleReply = SAMPLE_REPLIES[replyIndex];
        
        // Update the reply textarea
        const replyTextarea = document.getElementById('sp-reply');
        replyTextarea.value = sampleReply;
        replyTextarea.dispatchEvent(new Event('input'));
        
        // Hide loading state
        loadingElement.classList.add('hidden');
        replyContainer.style.opacity = '1';
        generateBtn.disabled = false;
        
        // Enable the post button
        document.getElementById('sp-post-btn').disabled = false;
        
        return;
      }
      
      // Call the background script to generate a reply
      try {
        console.log('Sending message to background script for reply generation');
        const response = await chrome.runtime.sendMessage({
          action: 'generate_reply',
          data: {
            originalPost: originalPost,
            tone: tone,
            longform: longform,
            emoji: emoji,
            hashtags: hashtags,
            replyTo: username,
            replyUrl: tweetUrl
          }
        });
        
        console.log('Response from background script:', response);
        
        // Hide loading state
        loadingElement.classList.add('hidden');
        replyContainer.style.opacity = '1';
        generateBtn.disabled = false;
        
        if (response && response.success) {
          console.log('Reply generated successfully:', response);
          
          // Update the reply textarea
          const replyTextarea = document.getElementById('sp-reply');
          replyTextarea.value = response.post;
          replyTextarea.dispatchEvent(new Event('input'));
          
          // Enable the post button
          document.getElementById('sp-post-btn').disabled = false;
        } else {
          console.error('Error generating reply:', response?.error || 'Unknown error');
          
          // Show error
          errorElement.textContent = response?.error || 'Failed to generate reply. Please try again.';
          errorElement.classList.remove('hidden');
        }
      } catch (fetchError) {
        console.error('Error when generating reply:', fetchError);
        
        // Hide loading state
        loadingElement.classList.add('hidden');
        replyContainer.style.opacity = '1';
        generateBtn.disabled = false;
        
        // Show a more detailed error message
        let errorMessage = 'Failed to connect to the SupaPost API. ';
        
        if (fetchError.message.includes('Failed to fetch')) {
          errorMessage += 'Make sure your local development server is running at supapost.test.';
        } else {
          errorMessage += fetchError.message || 'Please try again.';
        }
        
        errorElement.textContent = errorMessage;
        errorElement.classList.remove('hidden');
      }
    } catch (error) {
      console.error('Error generating reply:', error);
      
      // Show error message
      const errorElement = document.getElementById('sp-error');
      errorElement.textContent = error.message || 'An error occurred. Please try again.';
      errorElement.classList.remove('hidden');
      
      // Reset states
      document.getElementById('sp-loading').classList.add('hidden');
      document.getElementById('sp-reply-container').style.opacity = '1';
      document.getElementById('sp-generate-btn').disabled = false;
    }
  }
  
  // Post the reply to X.com
  async function postReply(tweetData) {
    try {
      const replyText = document.getElementById('sp-reply').value;
      if (!replyText) {
        console.error('No reply text to post');
        return;
      }
      
      console.log('Posting reply:', replyText);
      
      // Save the generated post to the API
      if (!LOCAL_TESTING_MODE) {
        try {
          await chrome.runtime.sendMessage({
            action: 'save_post',
            data: {
              content: replyText,
              topic: `Reply to tweet`,
              replyUrl: window.location.href,
              originalPost: tweetData.tweetText || ''
            }
          });
          console.log('Reply saved to SupaPost history');
        } catch (saveError) {
          console.error('Error saving reply to history:', saveError);
          // Non-fatal error, continue with posting
        }
      }
      
      // Find the textarea in the original X.com reply modal
      if (tweetData.modalElement) {
        // Find the textarea in the tweet modal
        const textarea = tweetData.modalElement.querySelector(REPLY_TEXTAREA_SELECTOR);
        if (textarea) {
          // Focus the textarea
          textarea.focus();
          
          // We need to simulate user typing to properly update the tweet
          // Using the clipboard API to paste the text
          try {
            // Save the original content
            const originalText = textarea.textContent;
            
            // Copy our reply to clipboard
            await navigator.clipboard.writeText(replyText);
            
            // Simulate a clipboard paste event
            document.execCommand('paste');
            
            console.log('Reply pasted into X.com textarea');
            
            // Allow X.com time to process the text update
            setTimeout(() => {
              // Close our modal
              closeModal();
              
              // Find and click the Reply button after a short delay
              setTimeout(() => {
                const replyButton = tweetData.modalElement.querySelector(REPLY_BUTTON_SELECTOR);
                if (replyButton) {
                  console.log('Clicking the X.com Reply button');
                  replyButton.click();
                } else {
                  console.error('Could not find the X.com Reply button');
                }
              }, 500);
            }, 500);
            
            return;
          } catch (clipboardError) {
            console.error('Clipboard error:', clipboardError);
            // Fall back to manual copying instructions
          }
        }
      }
      
      // If we can't paste automatically, show instructions to the user
      alert('Please copy the generated reply and paste it into the X.com reply box:\n\n' + replyText);
      
      // Close our modal so they can see the X.com reply modal
      closeModal();
    } catch (error) {
      console.error('Error posting reply:', error);
      alert('Error posting reply: ' + error.message);
    }
  }
  
  // Utility: Debounce function to limit how often a function can be called
  function debounce(func, delay) {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), delay);
    };
  }

  // This function is no longer used - keeping for reference
  function injectButtonsIntoTweets() {
    // No longer injecting buttons into tweet action bars
    console.log('Tweet button injection disabled - using reply modal injection instead');
  }

  // Add a small CSS style for our button
  function addSupaPostStyles() {
    const style = document.createElement('style');
    style.textContent = `
      .sp-reply-button {
        display: flex;
        align-items: center;
        background-color: #1d9bf0;
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 0 16px;
        font-weight: bold;
        height: 36px;
        margin-right: 12px;
        cursor: pointer;
        transition: background-color 0.2s;
      }
      
      .sp-reply-button:hover {
        background-color: #1a8cd8;
      }
      
      .sp-reply-button svg {
        margin-right: 8px;
      }
    `;
    document.head.appendChild(style);
  }
  
  // Call this on initialization
  addSupaPostStyles();

  // Function to scan the DOM for reply-like modals that might not match our selectors
  function scanForReplyModals() {
    // Prevent concurrent scanning
    if (isScanning) {
      console.log('Already scanning, skipping redundant scan');
      return;
    }
    
    // Throttle scanning (no more than once every 1 second)
    const now = Date.now();
    if (now - lastScanTime < 1000) {
      console.log('Throttling scan, too soon since last scan');
      return;
    }
    
    lastScanTime = now;
    isScanning = true;
    console.log('Starting controlled scan for reply modals...');
    
    try {
      // Only look for direct matches using our simplified selector
      const directMatches = document.querySelectorAll(REPLY_MODAL_SELECTOR);
      if (directMatches.length > 0) {
        console.log(`Found ${directMatches.length} modal matches`);
        
        // Process a maximum of 1 modal at a time to prevent excessive processing
        for (let i = 0; i < Math.min(directMatches.length, 1); i++) {
          const modal = directMatches[i];
          if (!modal.querySelector('.sp-reply-button')) {
            console.log('Processing modal match');
            injectButtonIntoReplyModal(modal);
            // Only process one modal per scan
            break;
          }
        }
      } else {
        console.log('No modals found with current selector');
      }
    } catch (error) {
      console.error('Error during modal scanning:', error);
    } finally {
      // Always release the scanning lock
      isScanning = false;
    }
  }
})(); 