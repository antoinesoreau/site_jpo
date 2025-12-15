CREATE TABLE `commentaire` (
    `id_commentaire` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `commentaire_nom` VARCHAR(255) NULL,
    `commentaire_ressenti` TINYINT(1) NOT NULL,
    `commentaire_note` TINYINT(1) NOT NULL,
    `id_public` INT UNSIGNED NOT NULL,
    `id_rubrique` INT UNSIGNED NOT NULL,
    `commentaire_texte` TEXT NULL,
    `commentaire_status` TINYINT(1) NOT NULL DEFAULT 0,
    
    PRIMARY KEY (`id_commentaire`),
    
    CONSTRAINT `fk_commentaire_public` 
        FOREIGN KEY (`id_public`) 
        REFERENCES `public` (`id_public`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
        
    CONSTRAINT `fk_commentaire_rubrique` 
        FOREIGN KEY (`id_rubrique`) 
        REFERENCES `rubrique` (`id_rubrique`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
);


CREATE TABLE `rubrique` (
    `id_rubrique` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `rubrique_nom` VARCHAR(255) NOT NULL,
    `rubrique_status` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_rubrique`),
    UNIQUE KEY `uk_rubrique_nom` (`rubrique_nom`)
);

CREATE TABLE `public` (
    `id_public` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `public_nom` VARCHAR(255) NOT NULL,
    `public_status` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_public`),
    UNIQUE KEY `uk_public_nom` (`public_nom`)
);

CREATE TABLE `pole` (
    `id_pole` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pole_nom` VARCHAR(255) NOT NULL,
    `pole_status` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_pole`),
    UNIQUE KEY `uk_pole_nom` (`pole_nom`)
);