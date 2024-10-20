import React, { useEffect, useState } from 'react';
import axios from 'axios';
import styles from '../mainPage.module.css';

const TotalReactions = () => {
  const [userReactions, setUserReactions] = useState([]);

  useEffect(() => {
    const fetchReactions = async () => {
      try {
        const response = await axios.get('/api/user-reactions'); // Adjust the API endpoint as needed
        setUserReactions(response.data);
      } catch (error) {
        console.error('Error fetching user reactions', error);
      }
    };

    fetchReactions();
  }, []);

  return (
    <div className={styles.reactionsContainer}>
      <h2>Total Reactions per User</h2>
      <ul>
        {userReactions.map(user => (
          <li key={user.user_id}>
            {user.username}: {user.total_reactions} reactions
          </li>
        ))}
      </ul>
    </div>
  );
};

export default TotalReactions;
