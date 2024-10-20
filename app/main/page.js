"use client";
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import styles from '../mainPage.module.css';
import Post from '../components/Post';

// Main Page Component
const Page = () => {
  const [posts, setPosts] = useState([]);
  const [thought, setThought] = useState('');
  const [user, setUser] = useState({ id: 1, name: '' }); // Empty name initially
  const [showModal, setShowModal] = useState(false);
  const [currentPostId, setCurrentPostId] = useState(null);

  // Fetch posts from the server when the component mounts
  useEffect(() => {
    const fetchPosts = async () => {
      try {
        const response = await axios.get('http://localhost/reactionjs/getPost.php');
        if (response.data.status === 'success') {
          setPosts(response.data.posts); // Assuming response contains posts array
        } else {
          console.error('Failed to fetch posts:', response.data.message);
        }
      } catch (error) {
        console.error('Error fetching posts:', error);
      }
    };

    fetchPosts();

    // Retrieve username from localStorage
    const storedUsername = localStorage.getItem('username');
    if (storedUsername) {
      setUser(prevUser => ({ ...prevUser, name: storedUsername }));
    }
  }, []);

  const handleSubmitPost = async (e) => {
    e.preventDefault();

    if (thought.trim()) {
      try {
        const response = await axios.post('http://localhost/reactionjs/post.php', {
          user_id: user.id,
          post_content: thought,
        }, {
          headers: {
            'Content-Type': 'application/json',
          },
        });

        if (response.data.status === 'success') {
          // Fetch updated posts
          const updatedPostsResponse = await axios.get('http://localhost/reactionjs/getPost.php');
          if (updatedPostsResponse.data.status === 'success') {
            setPosts(updatedPostsResponse.data.posts); // Update posts state
          }
          setThought(''); // Clear the input
        } else {
          console.error('Failed to add post:', response.data.message);
        }
      } catch (error) {
        console.error('Error saving post:', error);
      }
    } else {
      console.error('Post content required.');
    }
  };

  // Function to handle reactions (for Post.js)
  const handleReact = async (postId, reactionType) => {
    try {
      const response = await axios.post('http://localhost/reactionjs/react.php', {
        post_id: postId,
        user_id: user.id,
        reaction_type: reactionType,
      });

      if (response.data.status === 'success') {
        // Fetch updated posts
        const updatedPostsResponse = await axios.get('http://localhost/reactionjs/getPost.php');
        if (updatedPostsResponse.data.status === 'success') {
          setPosts(updatedPostsResponse.data.posts); // Update posts state
        }
      } else {
        console.error('Failed to add reaction:', response.data.message);
      }
    } catch (error) {
      console.error('Error adding reaction:', error);
    }
  };

  // Function to handle comments (for Post.js)
  const handleComment = async (postId, comment) => {
    try {
      const response = await axios.post('http://localhost/reactionjs/comment.php', {
        post_id: postId,
        user_id: user.id,
        comment,
      });

      if (response.data.status === 'success') {
        // Fetch updated posts
        const updatedPostsResponse = await axios.get('http://localhost/reactionjs/getPost.php');
        if (updatedPostsResponse.data.status === 'success') {
          setPosts(updatedPostsResponse.data.posts); // Update posts state
        }
      } else {
        console.error('Failed to add comment:', response.data.message);
      }
    } catch (error) {
      console.error('Error adding comment:', error);
    }
  };

  return (
    <>
      <h1>Welcome to the Hugot Page</h1>
      <p>Welcome, {user.name || 'Guest'}!</p> {/* Display the username */}
      <div className={styles.formContainer}>
        <form onSubmit={handleSubmitPost}>
          <textarea
            className={styles.textarea}
            value={thought}
            onChange={(e) => setThought(e.target.value)}
            placeholder="What's on your mind?" 
          />
          <button type="submit" className={styles.button}>Post</button>
        </form>
      </div>
      <div className={styles.postsContainer}>
        {posts.map(post => (
          <Post
            key={post.post_id}
            post={post}
            onReact={handleReact}
            onComment={handleComment}
            onShowModal={() => {
              setCurrentPostId(post.post_id);
              setShowModal(true);
            }}
          />
        ))}
      </div>
      {/* Your Modal component goes here if needed */}
    </>
  );
};

export default Page;
