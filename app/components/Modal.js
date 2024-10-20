import React from 'react';
import styles from '../mainPage.module.css'; // Adjust the path if needed

const Modal = ({ show, onClose, onReact }) => {
  if (!show) return null;

  const handleReact = (reactionType) => {
    onReact(reactionType); // Notify parent component
    onClose(); // Close the modal after reacting
  };

  return (
    <div className={styles.modalOverlay} onClick={onClose}>
      <div className={styles.modalContent} onClick={(e) => e.stopPropagation()}>
        <button className={styles.closeButton} onClick={onClose}>
          &times;
        </button>
        <h2>Select Reaction</h2>
        <div className={styles.reactions}>
          <button
            className={styles.reactionIcon}
            onClick={() => handleReact('heart')}
            title="Love"
          >
            ❤️
          </button>
          <button
            className={styles.reactionIcon}
            onClick={() => handleReact('laugh')}
            title="Care"
          >
            🤗
          </button>
          <button
            className={styles.reactionIcon}
            onClick={() => handleReact('like')}
            title="Like"
          >
            👍
          </button>
        </div>
      </div>
    </div>
  );
};

export default Modal;
