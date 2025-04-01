# SupaPost for X

A Chrome browser extension that allows you to reply to posts on X.com (formerly Twitter) using AI-generated content from your SupaPost account.

## Features

- Add a "Reply with SupaPost" button to posts on X.com
- Generate AI-powered replies directly on X.com
- Use your account credits from the SupaPost app
- Save your generated replies to your SupaPost history
- Customize reply tone and style
- Seamless integration with your workspace brand settings

## Installation

### From Chrome Web Store (Coming Soon)

1. Visit the Chrome Web Store
2. Search for "SupaPost for X"
3. Click "Add to Chrome"

### Manual Installation (Developer Mode)

1. Download or clone this repository
2. Open Chrome and navigate to `chrome://extensions/`
3. Enable "Developer mode" (toggle in the top-right corner)
4. Click "Load unpacked" and select the `extension` folder
5. The extension should now be installed and active

## Usage

1. Click the SupaPost extension icon and enter your API Key from the SupaPost dashboard
2. Configure your default reply preferences (tone, style options)
3. Browse to X.com (Twitter)
4. Hover over any post or reply to see the "SupaPost" button
5. Click the button to generate an AI-powered reply
6. Customize the generated reply if needed
7. Click "Post Reply" to publish it

## Configuration

- **API Key**: Required to connect to your SupaPost account. This can be generated in your SupaPost dashboard under Settings > API.
- **Reply Style**: Choose between casual, formal, humorous, or professional tones.
- **Options**: Enable or disable longform replies, emojis, and hashtags.

## How It Works

- The extension uses your API key to authenticate with your SupaPost account
- Your workspace branding and settings are automatically applied to generated replies
- Credits are deducted from your SupaPost account when you generate a reply
- All generated replies are saved to your post history in the SupaPost app

## Privacy

- Your API key is stored locally on your device and is only used to authenticate with the SupaPost API.
- The extension only activates on X.com domains.
- No data is collected beyond what's necessary for generating replies.

## Support

If you encounter any issues or have questions, please contact support at support@supapost.com or open an issue in this repository.

## License

[MIT License](LICENSE) 