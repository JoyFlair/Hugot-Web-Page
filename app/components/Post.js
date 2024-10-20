import React, { useState, useEffect } from 'react';
import { FaShare, FaThumbsUp, FaHeart, FaSmile } from 'react-icons/fa';
import axios from 'axios';
import styles from '../mainPage.module.css'; // Adjust the path if needed

const Post = ({ post, onComment, onShare = () => {} }) => {
  const [showCommentInput, setShowCommentInput] = useState(false);
  const [commentText, setCommentText] = useState('');
  const [modalVisible, setModalVisible] = useState(false);
  const [reactionModalVisible, setReactionModalVisible] = useState(false);
  const [commentModalVisible, setCommentModalVisible] = useState(false);
  const [comments, setComments] = useState(post.comments || []);
  const [shares, setShares] = useState(post.shares || 0);
  const [userReaction, setUserReaction] = useState();
  const [userId, setUserId] = useState(null); // Manage user ID

  const [reactions, setReactions] = useState({
    like: 0,
    heart: 0,
    care: 0,
  });

  const totalReactions = Object.values(reactions).reduce((total, count) => total + (count || 0), 0);
  const totalComments = comments.length;
  const totalShares = shares;

  const fetchReactions = async () => {
    try {
      const response = await axios.get(`http://localhost/reactionjs/getReactions.php?post_id=${post.post_id}`);
      if (response.data.status === 'success') {
        setReactions({
          like: response.data.reactions.like || 0,
          heart: response.data.reactions.heart || 0,
          care: response.data.reactions.care || 0,
        });
      } else {
        console.error('Failed to fetch reactions');
      }
    } catch (error) {
      console.error('Error fetching reactions:', error);
    }
  };

  useEffect(() => {
    fetchReactions();
  }, [post.post_id]);

  const handleCommentSubmit = async (e) => {
    e.preventDefault();
    if (commentText.trim()) {
      const newComment = { 
        comment_id: Date.now(), // Generate a unique ID or use server-provided ID
        comment: commentText,
      };
      setComments(prevComments => [...prevComments, newComment]);
      setCommentText('');
      setShowCommentInput(false);
  
      try {
        const response = await axios.post('http://localhost/reactionjs/saveComment.php', {
          post_id: post.post_id,
          user_id: 1, // Replace with actual user ID
          comment: commentText,
          comment_date: new Date().toISOString(),
        });
  
        if (response.data.status === 'success') {
          console.log('Comment saved successfully');
          onComment(commentText); // Notify parent component if needed
        } else {
          console.error('Failed to save comment:', response.data.message);
        }
      } catch (error) {
        console.error('Error saving comment:', error);
      }
    }
  };

  const handleReact = async (reactionType) => {
    const newReactions = { ...reactions };

    // If the user has already reacted with this type, remove the reaction
    if (userReaction === reactionType) {
        newReactions[reactionType] = Math.max(newReactions[reactionType] - 1, 0);
        setUserReaction(null); // Reset user's reaction
    } else {
        // If the user has reacted with a different type, decrement that reaction count
        if (userReaction) {
            newReactions[userReaction] = Math.max(newReactions[userReaction] - 1, 0);
        }
        // Increment the selected reaction type
        newReactions[reactionType] = (newReactions[reactionType] || 0) + 1;
        setUserReaction(reactionType); // Update user's reaction
    }

    setReactions(newReactions); // Update state with new reactions

    // Make the API call to update the backend
    try {
        const response = await axios.post('http://localhost/reactionjs/saveReaction.php', {
            post_id: post.post_id,
            user_id: userId, // Ensure this is set correctly
            reaction_type: reactionType,
            increment: userReaction === reactionType ? -1 : 1,
        });

        if (response.data.status === 'success') {
            console.log('Reaction updated successfully');
        } else {
            console.error('Failed to update reaction:', response.data.message);
        }
    } catch (error) {
        console.error('Error updating reaction:', error);
    }
};




  const handleShare = async () => {
    setShares(shares + 1);
    onShare();
  
    try {
      const response = await axios.post('http://localhost/reactionjs/updatePost.php', {
        post_id: post.post_id,
        update_field: 'total_shares',
        increment: 1
      });
  
      if (response.data.status === 'success') {
        console.log('Shares updated successfully');
      } else {
        console.error('Failed to update shares:', response.data.message);
      }
    } catch (error) {
      console.error('Error updating shares:', error);
    }
  };

  const CommentModal = ({ show, onClose, comments }) => {
    if (!show) return null;
  
    return (
      <div className={styles.modalOverlay}>
        <div className={styles.modalContent}>
          <button onClick={onClose}>Close</button>
          <h2>Comments</h2>
          <ul>
            {comments.map(comment => (
              <li key={comment.comment_id}>{comment.comment}</li>
            ))}
          </ul>
        </div>
      </div>
    );
  };

  useEffect(() => {
    const fetchComments = async () => {
      try {
        const response = await axios.get(`http://localhost/reactionjs/getcomment.php?post_id=${post.post_id}`);
        if (response.data.status === 'success') {
          setComments(response.data.comments || []);
        } else {
          console.error('Failed to fetch comments:', response.data.message);
        }
      } catch (error) {
        console.error('Error fetching comments:', error);
      }
    };
  
    fetchComments();
  }, [post.post_id]);

  return (
    <>
      <div className={styles.post}>
        <p>{post.post_content}</p>
        <div className={styles.reactionsContainer}>
          <span onClick={() => setReactionModalVisible(true)} style={{ cursor: 'pointer' }}>
            {totalReactions}
          </span>
          <span onClick={() => setCommentModalVisible(true)} style={{ cursor: 'pointer' }}>
            {totalComments}
          </span>
          <span>{totalShares}</span>
        </div>
        <div className={styles.buttonGroup}>
          <button onClick={() => setModalVisible(true)} className={styles.reactButton}>
            React
          </button>
          <button
            className={styles.commentButton}
            onClick={() => setShowCommentInput(!showCommentInput)}
          >
            Comment
          </button>
          <button onClick={handleShare} className={styles.shareButton}>
            <FaShare /> Share
          </button>
        </div>
        {showCommentInput && (
          <div className={styles.commentInputContainer}>
            <textarea
              className={styles.commentInput}
              value={commentText}
              onChange={(e) => setCommentText(e.target.value)}
              placeholder="Add a comment..."
            />
            <button
              className={styles.submitCommentButton}
              onClick={handleCommentSubmit}
            >
              ➤
            </button>
          </div>
        )}
      </div>

      {modalVisible && (
        <Modal
          show={modalVisible}
          onClose={() => setModalVisible(false)}
          onReact={handleReact}
        />
      )}

      {reactionModalVisible && (
        <div className={styles.modalOverlay} onClick={() => setReactionModalVisible(false)}>
          <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
            <button className={styles.closeButton} onClick={() => setReactionModalVisible(false)}>
              &times;
            </button>
            <h2>Reactions</h2>
            <div className={styles.reactions}>
              <div className={styles.reactionItem} onClick={() => handleReact('like')}>
                <FaThumbsUp />
                <span>{reactions.like || 0}</span>
              </div>
              <div className={styles.reactionItem} onClick={() => handleReact('heart')}>
                <FaHeart />
                <span>{reactions.heart || 0}</span>
              </div>
              <div className={styles.reactionItem} onClick={() => handleReact('care')}>
                <FaSmile />
                <span>{reactions.care || 0}</span>
              </div>
            </div>
          </div>
        </div>
      )}

      {commentModalVisible && (
        <CommentModal
          show={commentModalVisible}
          onClose={() => setCommentModalVisible(false)}
          comments={comments}
        />
      )}
    </>
  );
};

export default Post;
