// Configuration
const API_BASE_URL = 'http://supapost.test/api/extension';

// Debugging mode - log all API requests and responses
const DEBUG_MODE = true;

// Flag to toggle between real API and mock responses
const USE_MOCK_API = true; // Temporarily using mock data while debugging API issues

// Development mode - automatically accept any API key
const DEV_MODE = true; // Temporarily enabling dev mode for testing

// Log system info to help with debugging
console.log('SupaPost Extension Background Script loaded');
console.log('API Base URL:', API_BASE_URL);
console.log('Debug Mode:', DEBUG_MODE);
console.log('Using Mock API:', USE_MOCK_API);
console.log('Dev Mode:', DEV_MODE);

// Mock responses for testing
const MOCK_RESPONSES = {
  generate: {
    success: true,
    post: "This is a mock reply generated for testing purposes. It's designed to simulate what the real API would return when generating a reply.",
    remaining_credits: 95
  },
  save: {
    success: true,
    message: 'Post saved successfully'
  },
  workspace: {
    success: true,
    workspace: {
      name: 'Test Workspace',
      brand_voice: 'Professional but friendly',
      primary_color: '#1da1f2'
    }
  }
};

// Listen for messages from content script or popup
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
  // Handle message based on action type
  switch (request.action) {
    case 'generate_reply':
      handleGenerateReply(request.data)
        .then(sendResponse)
        .catch(error => sendResponse({ success: false, error: error.message }));
      return true; // Required for async sendResponse

    case 'save_post':
      handleSavePost(request.data)
        .then(sendResponse)
        .catch(error => sendResponse({ success: false, error: error.message }));
      return true;

    case 'get_workspace_settings':
      getWorkspaceSettings()
        .then(sendResponse)
        .catch(error => sendResponse({ success: false, error: error.message }));
      return true;
      
    case 'check_api_key':
      checkApiKey()
        .then(sendResponse)
        .catch(error => sendResponse({ success: false, error: error.message }));
      return true;
      
    case 'test_api':
      testApiEndpoint(request.endpoint, request.method, request.data)
        .then(sendResponse)
        .catch(error => sendResponse({ success: false, error: error.message }));
      return true;
  }
});

// Test API endpoint directly
async function testApiEndpoint(endpoint, method = 'GET', data = null) {
  try {
    const apiKey = await getApiKey();
    if (!apiKey) {
      throw new Error('API key required');
    }
    
    const url = `${API_BASE_URL}/${endpoint}`;
    log(`Testing API endpoint: ${url} with method: ${method}`);
    console.log(`Testing API endpoint: ${url} with method: ${method} and key: ${apiKey.substring(0, 4)}...`);
    
    // For testing without real token exchange
    const headers = {
      'Authorization': `Bearer ${apiKey}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
    
    const options = {
      method,
      headers,
      credentials: 'include'
    };
    
    if (method !== 'GET' && data) {
      options.body = JSON.stringify(data);
    }
    
    log(`Making API request with options:`, options);
    
    try {
      const response = await fetch(url, options);
      log(`Response status: ${response.status}`);
      console.log(`API response status: ${response.status}`);
      
      let responseData;
      try {
        responseData = await response.json();
        log('Response data:', responseData);
        console.log('API response data:', responseData);
      } catch (e) {
        const text = await response.text();
        log('Response text:', text);
        console.log('API response text:', text);
        responseData = { text };
      }
      
      return {
        success: response.ok,
        status: response.status,
        data: responseData
      };
    } catch (fetchError) {
      console.error('Fetch error:', fetchError);
      
      if (USE_MOCK_API) {
        console.log('Fetch failed but using mock API data as fallback');
        // Return mock data based on endpoint
        if (endpoint === 'generate') {
          return { success: true, status: 200, data: MOCK_RESPONSES.generate };
        } else if (endpoint === 'save') {
          return { success: true, status: 200, data: MOCK_RESPONSES.save };
        } else if (endpoint === 'user' || endpoint === 'credits') {
          return { 
            success: true, 
            status: 200, 
            data: { 
              credits: 100, 
              user: { 
                name: "Test User", 
                email: "test@example.com" 
              } 
            } 
          };
        }
        
        return { success: true, status: 200, data: { message: "Mock response" } };
      }
      
      throw fetchError;
    }
  } catch (error) {
    console.error('API test error:', error);
    throw error;
  }
}

// Simple logger function that only logs in debug mode
function log(...args) {
  if (DEBUG_MODE) {
    console.log(...args);
  }
}

// Handle generating a reply
async function handleGenerateReply(data) {
  try {
    // For local testing, return mock response
    if (USE_MOCK_API) {
      log('Using mock API for reply generation');
      // Simulate network delay
      await new Promise(resolve => setTimeout(resolve, 1000));
      return MOCK_RESPONSES.generate;
    }
    
    log('Using actual API for reply generation');
    
    // Get API key
    const apiKey = await getApiKey();
    if (!apiKey) {
      throw new Error('API key required. Please add your API key in the extension settings.');
    }
    
    // Get settings
    const settings = await getSettings();
    
    // Build request payload
    const payload = {
      topic: data.originalPost || 'Reply',
      tone: data.tone || settings?.defaultTone || 'casual',
      longform: data.longform || settings?.longform || false,
      emoji: data.emoji || settings?.emoji || false,
      hashtags: data.hashtags || settings?.hashtags || false,
      replyTo: data.replyTo || '',
      originalPost: data.originalPost || ''
    };
    
    // Prepare headers
    const headers = {
      'Authorization': `Bearer ${apiKey}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
    
    log('Making API call to:', `${API_BASE_URL}/generate`);
    log('With payload:', payload);
    
    // Call API
    const response = await fetch(`${API_BASE_URL}/generate`, {
      method: 'POST',
      headers,
      body: JSON.stringify(payload),
      credentials: 'include'
    });
    
    log('API response status:', response.status);
    
    if (!response.ok) {
      let errorMessage = 'Failed to generate reply';
      try {
        const errorData = await response.json();
        console.error('API error:', errorData);
        errorMessage = errorData.message || errorData.error || errorMessage;
      } catch (e) {
        console.error('Could not parse error response:', e);
        // Try to get text response if JSON parsing fails
        const textResponse = await response.text();
        console.error('Raw error response:', textResponse);
      }
      throw new Error(errorMessage);
    }
    
    const responseData = await response.json();
    log('API response data:', responseData);
    
    // Return generated content
    return {
      success: true,
      post: responseData.post || responseData.content || responseData.message,
      remaining_credits: responseData.remaining_credits || responseData.credits || 0
    };
  } catch (error) {
    console.error('Generate reply error:', error);
    throw error;
  }
}

// Handle saving a post
async function handleSavePost(data) {
  try {
    // For local testing, return mock response
    if (USE_MOCK_API) {
      log('Using mock API for saving post');
      // Simulate network delay
      await new Promise(resolve => setTimeout(resolve, 800));
      return MOCK_RESPONSES.save;
    }
    
    log('Using actual API for saving post');
    
    // Get API key
    const apiKey = await getApiKey();
    if (!apiKey) {
      throw new Error('API key required. Please add your API key in the extension settings.');
    }
    
    // Prepare headers
    const headers = {
      'Authorization': `Bearer ${apiKey}`,
      'X-API-Key': apiKey,
      'api-key': apiKey,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
    
    // Call API
    const response = await fetch(`${API_BASE_URL}/save`, {
      method: 'POST',
      headers,
      body: JSON.stringify({
        content: data.content,
        topic: data.topic || 'Reply',
        is_reply: true,
        reply_to_url: data.replyUrl || '',
        original_post: data.originalPost || '',
        api_key: apiKey
      }),
      credentials: 'include'
    });
    
    if (!response.ok) {
      let errorMessage = 'Failed to save post';
      try {
        const errorData = await response.json();
        console.error('API error:', errorData);
        errorMessage = errorData.message || errorMessage;
      } catch (e) {
        console.error('Could not parse error response:', e);
      }
      throw new Error(errorMessage);
    }
    
    const responseData = await response.json();
    
    return {
      success: true,
      message: 'Post saved successfully'
    };
  } catch (error) {
    console.error('Save post error:', error);
    throw error;
  }
}

// Get workspace settings
async function getWorkspaceSettings() {
  try {
    // For local testing, return mock response
    if (USE_MOCK_API) {
      log('Using mock API for workspace settings');
      // Simulate network delay
      await new Promise(resolve => setTimeout(resolve, 500));
      return MOCK_RESPONSES.workspace;
    }
    
    log('Using actual API for workspace settings');
    
    // Get API key
    const apiKey = await getApiKey();
    if (!apiKey) {
      throw new Error('API key required. Please add your API key in the extension settings.');
    }
    
    // Prepare headers
    const headers = {
      'Authorization': `Bearer ${apiKey}`,
      'X-API-Key': apiKey,
      'api-key': apiKey,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
    
    // Call API - first try user endpoint to get workspace info
    const response = await fetch(`${API_BASE_URL}/user`, {
      method: 'GET',
      headers,
      credentials: 'include'
    });
    
    if (!response.ok) {
      let errorMessage = 'Failed to get workspace settings';
      try {
        const errorData = await response.json();
        console.error('API error:', errorData);
        errorMessage = errorData.message || errorMessage;
      } catch (e) {
        console.error('Could not parse error response:', e);
      }
      throw new Error(errorMessage);
    }
    
    const userData = await response.json();
    
    // Extract workspace info from user data
    return {
      success: true,
      workspace: {
        name: userData.workspace?.name || 'Your Workspace',
        brand_voice: userData.workspace?.brand_voice || 'Professional'
      }
    };
  } catch (error) {
    console.error('Get workspace settings error:', error);
    throw error;
  }
}

// Check API key validity - simplified version that works with different key formats
async function checkApiKey() {
  try {
    // Get API key
    const apiKey = await getApiKey();
    
    if (!apiKey) {
      return { valid: false };
    }
    
    log('Checking API key validity:', apiKey);
    
    // In development mode, always accept any API key
    if (DEV_MODE) {
      log('Development mode enabled - accepting any API key');
      return {
        valid: true,
        workspace: {
          name: 'Development Workspace',
          brand_voice: 'Professional'
        }
      };
    }
    
    // For testing or local dev mode, accept any non-empty key
    if (USE_MOCK_API) {
      log('Using mock validation - accepting any key');
      return {
        valid: true,
        workspace: MOCK_RESPONSES.workspace.workspace
      };
    }
    
    // Simple API key validation - try endpoints with the key
    try {
      // Prepare headers with multiple auth methods for compatibility
      const headers = {
        'Authorization': `Bearer ${apiKey}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      };
      
      // Try the credits endpoint first (should be simple and lightweight)
      log('Validating key with credits endpoint');
      const creditsResponse = await fetch(`${API_BASE_URL}/credits`, {
        method: 'GET',
        headers,
        credentials: 'include'
      });
      
      // If we get a successful response, the key is valid
      if (creditsResponse.ok) {
        log('API key validation successful');
        const creditsData = await creditsResponse.json();
        log('Credits data:', creditsData);
        
        // Try to get workspace settings if available
        let workspace = null;
        try {
          const workspaceResponse = await fetch(`${API_BASE_URL}/user`, {
            method: 'GET',
            headers,
            credentials: 'include'
          });
          
          if (workspaceResponse.ok) {
            const userData = await workspaceResponse.json();
            log('User data:', userData);
            
            workspace = {
              name: userData.name || "Default Workspace",
              brand_voice: userData.settings?.brand_voice || "Professional"
            };
          }
        } catch (workspaceError) {
          log('Error getting workspace settings:', workspaceError);
          // Non-fatal error, continue with validation result
        }
        
        return {
          valid: true,
          workspace: workspace,
          credits: creditsData.credits || 0
        };
      } else {
        // Try to parse error message
        let errorMessage = 'API key validation failed';
        try {
          const errorData = await creditsResponse.json();
          errorMessage = errorData.message || errorData.error || errorMessage;
          log('API key validation error:', errorData);
        } catch (e) {
          log('Could not parse error response');
        }
        
        log('API key validation failed with status:', creditsResponse.status);
        return {
          valid: false,
          error: errorMessage
        };
      }
    } catch (error) {
      log('API validation error:', error);
      return {
        valid: false,
        error: error.message
      };
    }
  } catch (error) {
    console.error('Check API key error:', error);
    
    // In case of any error with validation process, default to invalid
    return {
      valid: false,
      error: error.message
    };
  }
}

// Storage utilities
async function getApiKey() {
  const result = await chrome.storage.local.get('apiKey');
  return result.apiKey;
}

async function getSettings() {
  const result = await chrome.storage.local.get('settings');
  return result.settings;
} 