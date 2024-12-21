<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Home</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">SocialHub</div>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <div class="nav-icons">
                <a href="#">🔔</a>
                <a href="#">✉️</a>
                <a href="studentProfile.php">👤</a>
            </div>
        </nav>
    </header>
    <div class="container">
        <!-- <aside class="sidebar">
            <ul>
                <li><a href="#">🏠 Home</a></li>
                <li><a href="#">📷 Explore</a></li>
                <li><a href="#">🧑‍🤝‍🧑 Friends</a></li>
                <li><a href="#">⚙️ Settings</a></li>
            </ul>
        </aside> -->
        <main class="feed">
            <div class="add-post">
                <h3>Create a Post</h3>
                <form>
                    <textarea placeholder="What's on your mind?" rows="3"></textarea>
                    <div class="post-actions">
                        <label for="upload-photo">📷 Add Photo</label>
                        <input type="file" id="upload-photo" style="display: none;">
                        <button type="submit">Post</button>
                    </div>
                </form>
            </div>

           <div class="post">
    <div class="post-header">
        <img src="./images/sampleprofile.jpg" alt="John Doe" class="profile-pic">
        <h3>John Doe</h3>
    </div>
    <p>Just had an amazing day at the beach! 🌊☀️</p>
    <img src="./images/bg.jpg" alt="Post image" class="post-image">
    <div class="post-interactions">
        <button class="like-btn">👍 Like</button>
        <button class="comment-btn">💬 Comment</button>
    </div>
    <div class="comments">
        <!-- Pre-filled Comment -->
        <div class="comment">
            <img src="./images/sampleprofilewoman.jpg" alt="Jane Smith" class="post-image">
            <strong>Jane Smith:</strong> Wow, that looks amazing! 🌟
            <div class="comment-actions">
                <button class="like-comment-btn">👍 Like</button>
                <button class="reply-btn">💬 Reply</button>
            </div>
        </div>
        <div class="comment">
            <img src="./images/sampleprofilewoman.jpg" alt="Jane Smith" class="post-image">
            <strong>Jane Smith:</strong> Wow, that looks amazing! 🌟
            <div class="comment-actions">
                <button class="like-comment-btn">👍 Like</button>
                <button class="reply-btn">💬 Reply</button>
            </div>
        </div>
        <div class="comment">
            <img src="./images/sampleprofilewoman.jpg" alt="Jane Smith" class="post-image">
            <strong>Jane Smith:</strong><br> Wow, that looks amazing! 🌟
            <div class="comment-actions">
                <button class="like-comment-btn">👍 Like</button>
                <button class="reply-btn">💬 Reply</button>
            </div>
        </div>
        <div class="comment-form">
            <textarea placeholder="Write a comment..." rows="2"></textarea>
            <button class="submit-comment-btn">Post</button>
        </div>
    </div>
</div>

        </main>
        <aside class="trending">
            <h4>Trending</h4>
            <ul>
                <li>#SunsetViews</li>
                <li>#TravelGoals</li>
                <li>#TechTrends</li>
            </ul>
        </aside>
    </div>
    <footer>
        <p>&copy; 2024 SocialHub. All rights reserved.</p>
    </footer>
</body>
</html>
