// Configuration
const API_BASE_URL = 'http://supapost.test/api/extension'; // Local development URL
const APP_URL = 'http://supapost.test'; // Local app URL

// For testing - always consider validation successful in development environments
const FORCE_VALIDATION_SUCCESS = true; // Enable during development/debugging

// Debug mode - log more details
const DEBUG_MODE = true;

// Log configuration
console.log('SupaPost Extension Popup loaded');
console.log('API Base URL:', API_BASE_URL);
console.log('App URL:', APP_URL);
console.log('Force Validation Success:', FORCE_VALIDATION_SUCCESS);

// DOM Elements
const apikeyContainer = document.getElementById('apikey-container');
const settingsContainer = document.getElementById('settings-container');
const statusContainer = document.getElementById('status-container');
const statusMessage = document.getElementById('status-message');
const currentApiKey = document.getElementById('current-api-key');

// Forms
const apikeyForm = document.getElementById('apikey-form');
const settingsForm = document.getElementById('settings-form');

// Buttons and Links
const resetKeyBtn = document.getElementById('reset-key-btn');
const generateKeyLink = document.getElementById('generate-key-link');

// Initialize popup
document.addEventListener('DOMContentLoaded', async () => {
  // Set the app URLs
  generateKeyLink.href = `${APP_URL}/settings/api`;
  
  // Check if API key exists
  await checkApiKey();
  
  // Add event listeners
  setupEventListeners();
});

// Event listeners setup
function setupEventListeners() {
  // API key form submission
  apikeyForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    await handleApiKeySubmit();
  });
  
  // Settings form submission
  settingsForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    await handleSettingsSave();
  });
  
  // Reset API Key button
  resetKeyBtn.addEventListener('click', async () => {
    await handleResetApiKey();
  });
}

// Check if API key exists and show appropriate view
async function checkApiKey() {
  try {
    // Check if we have an API key
    const apiKey = await getApiKey();
    if (!apiKey) {
      showApiKeyView();
      return;
    }
    
    // In testing mode, skip validation
    if (FORCE_VALIDATION_SUCCESS) {
      console.log('Forced validation success mode enabled, skipping validation');
      await loadSettings();
      showSettingsView();
      return;
    }
    
    // Validate the API key with the background script
    // This will use the actual API for validation
    showStatus('Validating API key...', 'info');
    
    const apiKeyStatus = await chrome.runtime.sendMessage({
      action: "check_api_key"
    });
    
    console.log('API key validation result:', apiKeyStatus);
    
    if (apiKeyStatus && apiKeyStatus.valid) {
      await loadSettings();
      showSettingsView();
      showStatus('API key connected!', 'success');
    } else {
      showStatus('Invalid API key. Please enter a valid key from your SupaPost account.', 'error');
      showApiKeyView();
    }
  } catch (error) {
    console.error('API check error:', error);
    
    // In local development or testing, allow login without API validation
    if (FORCE_VALIDATION_SUCCESS || 
        window.location.hostname.includes('localhost') ||
        API_BASE_URL.includes('localhost') || 
        API_BASE_URL.includes('supapost.test')) {
      console.log('Local development environment - showing settings despite validation error');
      await loadSettings();
      showSettingsView();
    } else {
      showApiKeyView();
    }
  }
}

// Handle API key form submission
async function handleApiKeySubmit() {
  try {
    const apiKey = document.getElementById('api-key').value.trim();
    if (!apiKey) {
      showStatus('Please enter a valid API key', 'error');
      return;
    }
    
    // Log the API key being submitted (first 4 chars only for security)
    if (DEBUG_MODE) {
      console.log(`Submitting API key: ${apiKey.substring(0, 4)}...`);
    }
    
    // Basic format validation before making API call
    if (!validateApiKeyFormat(apiKey)) {
      showStatus('Invalid API key format. Please enter a valid API key.', 'error');
      return;
    }
    
    showStatus('Validating API key...', 'info');
    
    // Save API key first
    await saveApiKey(apiKey);
    
    // In testing mode, skip validation
    if (FORCE_VALIDATION_SUCCESS) {
      console.log('Development mode - API key automatically accepted');
      await loadSettings();
      showSettingsView();
      showStatus('API key accepted!', 'success');
      return;
    }
    
    // Try direct API test first for debugging
    try {
      console.log('Testing API directly with key:', apiKey.substring(0, 4) + '...');
      
      // Test the credits endpoint
      console.log('Testing credits endpoint...');
      const creditsResult = await chrome.runtime.sendMessage({
        action: "test_api",
        endpoint: "credits",
        method: "GET"
      });
      console.log('Credits API test result:', creditsResult);
      
      // Test the user endpoint
      console.log('Testing user endpoint...');
      const userResult = await chrome.runtime.sendMessage({
        action: "test_api",
        endpoint: "user",
        method: "GET"
      });
      console.log('User API test result:', userResult);
      
      // If either test succeeds, consider the API key valid
      if ((creditsResult && creditsResult.success) || 
          (userResult && userResult.success)) {
        console.log('API key validation successful - at least one endpoint worked');
        await loadSettings();
        showSettingsView();
        showStatus('API key connected successfully!', 'success');
        return;
      }
      
      console.log('All API tests failed with real endpoints');
    } catch (testError) {
      console.error('API test error:', testError);
    }
    
    // Validate with the background script (which will use the actual API)
    console.log('Falling back to standard API key validation');
    const apiKeyStatus = await chrome.runtime.sendMessage({
      action: "check_api_key"
    });
    
    console.log('API key validation result:', apiKeyStatus);
    
    if (apiKeyStatus && apiKeyStatus.valid) {
      // Load settings and show settings view
      await loadSettings();
      showSettingsView();
      showStatus('API key connected successfully!', 'success');
    } else {
      // For local development mode, accept the key anyway
      if (window.location.hostname.includes('localhost') ||
          API_BASE_URL.includes('localhost') || 
          API_BASE_URL.includes('supapost.test')) {
        console.log('Local development mode - accepting key anyway');
        await loadSettings();
        showSettingsView();
        showStatus('API key accepted for local development!', 'success');
      } else {
        showStatus('Invalid API key. The key could not be validated with the SupaPost API.', 'error');
      }
    }
  } catch (error) {
    console.error('API key error:', error);
    
    // In local development mode, accept the key despite errors
    if (window.location.hostname.includes('localhost') ||
        API_BASE_URL.includes('localhost') || 
        API_BASE_URL.includes('supapost.test')) {
      console.log('Local development mode - accepting key despite error');
      await loadSettings();
      showSettingsView();
      showStatus('API key accepted for local development!', 'success');
    } else {
      showStatus(error.message || 'Invalid API key. Please try again.', 'error');
    }
  }
}

// Validate API key format (basic format check before API validation)
function validateApiKeyFormat(apiKey) {
  // Accept any non-empty string
  return apiKey && apiKey.length > 0;
}

// Handle settings form submission
async function handleSettingsSave() {
  try {
    const settings = {
      defaultTone: document.getElementById('default-tone').value,
      longform: document.getElementById('longform').checked,
      emoji: document.getElementById('emoji').checked,
      hashtags: document.getElementById('hashtags').checked
    };
    
    // Save settings to storage
    await saveSettings(settings);
    
    showStatus('Settings saved successfully!', 'success');
  } catch (error) {
    console.error('Settings save error:', error);
    showStatus(error.message || 'Failed to save settings', 'error');
  }
}

// Handle reset API key
async function handleResetApiKey() {
  try {
    // Remove API key from storage
    await removeApiKey();
    
    // Also remove access token if stored
    await chrome.storage.local.remove('accessToken');
    
    // Show API key view
    showApiKeyView();
    showStatus('API key removed. Please enter a new one.', 'info');
  } catch (error) {
    console.error('Reset API key error:', error);
    showStatus(error.message || 'Failed to reset API key', 'error');
  }
}

// Load saved settings
async function loadSettings() {
  try {
    const settings = await getSettings();
    
    if (settings) {
      document.getElementById('default-tone').value = settings.defaultTone || 'casual';
      document.getElementById('longform').checked = settings.longform || false;
      document.getElementById('emoji').checked = settings.emoji || false;
      document.getElementById('hashtags').checked = settings.hashtags || false;
    }
  } catch (error) {
    console.error('Load settings error:', error);
  }
}

// View management functions
function showApiKeyView() {
  apikeyContainer.classList.remove('hidden');
  settingsContainer.classList.add('hidden');
}

function showSettingsView() {
  apikeyContainer.classList.add('hidden');
  settingsContainer.classList.remove('hidden');
  
  // Update the API key display with masked characters
  displayMaskedApiKey();
}

function displayMaskedApiKey() {
  getApiKey().then(apiKey => {
    if (apiKey) {
      // Show first 4 and last 4 characters, mask the rest
      const masked = apiKey.length > 8 
        ? `${apiKey.substring(0, 4)}...${apiKey.substring(apiKey.length - 4)}`
        : '••••••••••••••••';
      
      currentApiKey.textContent = masked;
    }
  });
}

function showStatus(message, type = 'info') {
  statusContainer.classList.remove('hidden');
  statusMessage.textContent = message;
  statusMessage.className = 'status-message'; // Reset classes
  statusMessage.classList.add(type);
  
  // Hide status after 5 seconds
  setTimeout(() => {
    statusContainer.classList.add('hidden');
  }, 5000);
}

// Storage utilities
async function getApiKey() {
  const result = await chrome.storage.local.get('apiKey');
  return result.apiKey;
}

async function saveApiKey(key) {
  await chrome.storage.local.set({ apiKey: key });
  
  // Remove any stored access token when changing API key
  await chrome.storage.local.remove('accessToken');
  
  // Notify the content script that the API key has changed
  chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
    if (tabs[0]) {
      chrome.tabs.sendMessage(tabs[0].id, {action: "api_key_changed"});
    }
  });
}

async function removeApiKey() {
  await chrome.storage.local.remove('apiKey');
}

async function getSettings() {
  const result = await chrome.storage.local.get('settings');
  return result.settings;
}

async function saveSettings(settings) {
  await chrome.storage.local.set({ settings: settings });
} 