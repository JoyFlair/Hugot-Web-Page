import React from 'react';

function ReactionButton({ user, postId }) {
    const handleReaction = async (postId, reactionType) => {
      try {
        const response = await axios.post('http://localhost/reactionjs/reaction.php', {
          post_id: postId,
          reaction_type: reactionType,
        });
    
        if (response.data.status === 'success') {
          // Handle success, e.g., update state
          console.log('Reaction recorded successfully');
        } else {
          console.error('Failed to record reaction:', response.data.message);
        }
      } catch (error) {
        console.error('Error recording reaction:', error);
      }
    };
    

  return (
    <div>
      <button onClick={() => handleReaction('love')}>Love</button>
      <button onClick={() => handleReaction('care')}>Care</button>
      <button onClick={() => handleReaction('like')}>Like</button>
    </div>
  );
}

export default ReactionButton;
