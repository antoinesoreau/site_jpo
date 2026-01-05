CREATE TABLE reels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- L'ID de l'utilisateur qui poste
    title VARCHAR(255) NOT NULL,
    description TEXT, -- Ici sera stocké le HTML du WYSIWYG
    video_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,      -- Le @pseudo (unique)
    email VARCHAR(100) NOT NULL UNIQUE,        -- Pour la connexion/notifs
    password_hash VARCHAR(255) NOT NULL,       -- Le mot de passe haché (jamais en clair !)
    full_name VARCHAR(100),                    -- Nom affiché (ex: Jean Dupont)
    bio TEXT,                                  -- Petite description de profil
    avatar_url VARCHAR(255) DEFAULT 'default.png', -- Photo de profil
    is_verified TINYINT(1) DEFAULT 0,          -- Le petit badge bleu (0 ou 1)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;