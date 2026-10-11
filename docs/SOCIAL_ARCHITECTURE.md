# Social Network Domain Architecture

## Overview

The social domain manages user profiles, content creation, social graph relationships, communities, messaging, and content discovery. It supports ActivityPub federation for decentralized social networking.

## Profile System

| Field | Description |
|-------|-------------|
| `user_id` | Internal unique identifier |
| `handle` | Unique @username |
| `display_name` | Public display name |
| `bio` | Profile description |
| `avatar_url` | Profile image URL |
| `banner_url` | Header image URL |
| `verified` | Verification status |
| `created_at` | Account creation date |

### Verification

- Email verification (mandatory)
- Phone verification (optional)
- Identity verification (government ID)
- Domain verification (for organizations)

### Privacy and Blocking

- Profile visibility: `public`, `followers_only`, `private`
- Block list with reason and timestamp
- Muted users (content hidden without notification)
- Domain blocks for federation

## Content System

### Content Types

| Type | Description |
|------|-------------|
| `post` | Short-form text (max 500 chars) |
| `article` | Long-form content with title |
| `media` | Image/video with optional caption |
| `poll` | Voting with options and expiry |
| `event` | Scheduled gathering with location |

### Content Lifecycle

```
draft → scheduled → published → edited → deleted
```

- Drafts auto-save with version history
- Scheduled posts publish at specified time
- Edits tracked with edit history visibility
- Soft delete with 30-day recovery window

### Media Handling

- Image upload with automatic resizing (thumbnail, medium, large)
- Video transcoding to multiple formats
- Alt text required for accessibility
- Content warnings for sensitive media

## Social Graph

### Relationships

| Type | Description |
|------|-------------|
| `follow` | One-way subscription to content |
| `friend` | Mutual connection |
| `list` | Curated group of users |
| `block` | Content and interaction suppression |

### Suggestions

- Mutual connection analysis
- Interest-based recommendations
- Contact import matching
- Graph traversal for degree-of-separation suggestions

## Community System

### Groups

| Field | Description |
|-------|-------------|
| `group_id` | Unique identifier |
| `name` | Group name |
| `visibility` | `public`, `private`, `secret` |
| `join_policy` | `open`, `approval`, `invite` |

### Roles and Permissions

| Role | Permissions |
|------|-------------|
| `member` | Read, post, comment |
| `moderator` | + Remove content, mute members |
| `admin` | + Manage settings, roles, membership |
| `owner` | Full control including deletion |

### Moderation

- Report queue with priority scoring
- Automated rule enforcement (keyword, pattern)
- Escalation paths for complex cases
- Appeal process with independent review

## Messaging System

### Conversations

- Direct messages (1:1)
- Group conversations (up to 100 participants)
- Message types: text, media, voice, location

### Delivery and Retention

- Real-time delivery via WebSockets
- Push notifications for offline users
- Message retention policies per conversation
- Ephemeral messages with configurable TTL

### Safety

- Message scanning for prohibited content
- Blocked user message filtering
- Report message with context
- Rate limiting to prevent spam

## Discovery System

### Search

- Full-text search across posts, profiles, and groups
- Hashtag indexing and trending
- Filtered search by type, date, location
- Search suggestions and autocomplete

### Trends

- Trending hashtags (real-time and daily)
- Trending topics with categorization
- Localized trends by region
- Trend suppression for sensitive content

### Recommendations

- Content recommendation based on engagement
- "Who to follow" suggestions
- Group recommendations
- Personalized feed ranking algorithm

## Moderation System

### Report Workflow

```
report_filed → auto_triage → moderator_review → action_taken → appeal_window
```

### Sanctions

| Sanction | Duration | Scope |
|----------|----------|-------|
| Warning | Permanent record | Account |
| Content removal | Immediate | Specific content |
| Temporary mute | 1-7 days | Cannot post/comment |
| Temporary suspension | 1-30 days | Cannot access account |
| Permanent ban | Indefinite | Account deletion |

### Evidence

- Screenshot and metadata preservation
- Reporter anonymity protection
- Audit log of all moderation actions
- Transparency report generation

## Notification System

### Channels

| Channel | Description |
|---------|-------------|
| `in_app` | Notification center |
| `email` | Digest or immediate |
| `push` | Mobile push notifications |
| `sms` | Critical alerts only |

### Preferences

- Per-event-type channel selection
- Quiet hours with timezone awareness
- Digest frequency: real-time, daily, weekly
- Mute specific users or keywords

## ActivityPub Federation

### Federation Architecture

```
┌─────────────┐     ActivityPub      ┌─────────────┐
│  Local      │◄────────────────────►│  Remote     │
│  Instance   │   (HTTP Signatures)  │  Instance   │
└─────────────┘                      └─────────────┘
```

### Core Components

| Component | Purpose |
|-----------|---------|
| `Actor` | User or service identity |
| `Inbox` | Receives activities from remote actors |
| `Outbox` | Sends activities to remote actors |
| `Object` | Content (posts, profiles, media) |

### Delivery

- HTTP Signatures for actor authentication
- Retry with exponential backoff for failed deliveries
- Shared inbox for efficiency
- Relay servers for discovery

## Module Boundaries and Extension Points

| Module | Extension Point |
|--------|---------------|
| `Profiles` | Custom profile fields, verification providers |
| `Content` | Custom content types, media processors |
| `SocialGraph` | Custom relationship types, suggestion algorithms |
| `Communities` | Custom roles, moderation rules |
| `Messaging` | Custom message types, delivery channels |
| `Discovery` | Custom ranking signals, trend algorithms |
| `Moderation` | Custom rules, sanction types |
| `Federation` | Custom activity types, relay integrations |

- Event-driven architecture with Laravel Events
- Policy-based authorization per module
- Adapter pattern for federation protocol versions
