

-- -----------------------------------------------------
-- Table `#__fittizen_nichos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_nichos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_nichos_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_nichos_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nicho_id` INT NOT NULL,
  `lang_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(45) NULL,
  `url` TEXT NULL,
  `description` TEXT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fnl_nichos_id_idx` (`nicho_id` ASC),
  INDEX `fnl_lang_id_idx` (`lang_id` ASC),
  CONSTRAINT `fnl_nichos_id`
    FOREIGN KEY (`nicho_id`)
    REFERENCES `#__fittizen_nichos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fnl_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `address` TEXT NULL,
  `neighborhood` TEXT NULL,
  `locality` TEXT NULL,
  `sublocality` TEXT NULL,
  `lat` DOUBLE NULL,
  `lng` DOUBLE NULL,
  `administrative_area_level_2` TEXT NULL,
  `administrative_area_level_1` TEXT NULL,
  `country` TEXT NULL,
  `country_short_name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_gyms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_gyms` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `location_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitgym_location_id_idx` (`location_id` ASC),
  CONSTRAINT `fitgym_location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `#__fittizen_locations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_gyms_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_gyms_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_id` INT UNSIGNED NOT NULL,
  `gym_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `url` TEXT NULL,
  `description` TEXT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fgl_gym_id_idx` (`gym_id` ASC),
  INDEX `flg_lang_id_idx` (`lang_id` ASC),
  CONSTRAINT `fgl_gym_id`
    FOREIGN KEY (`gym_id`)
    REFERENCES `#__fittizen_gyms` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `flg_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_diets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_diets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_diets_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_diets_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_id` INT UNSIGNED NOT NULL,
  `diet_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `url` TEXT NULL,
  `description` TEXT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fdl_lang_id_idx` (`lang_id` ASC),
  INDEX `fdl_diet_id_idx` (`diet_id` ASC),
  CONSTRAINT `fdl_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fdl_diet_id`
    FOREIGN KEY (`diet_id`)
    REFERENCES `#__fittizen_diets` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_supplements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_supplements` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_supplements_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_supplements_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_id` INT UNSIGNED NOT NULL,
  `supplement_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `url` TEXT NULL,
  `description` TEXT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fsl_lang_id_idx` (`lang_id` ASC),
  INDEX `fsl_supplement_id_idx` (`supplement_id` ASC),
  CONSTRAINT `fsl_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fsl_supplement_id`
    FOREIGN KEY (`supplement_id`)
    REFERENCES `#__fittizen_supplements` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_goals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_goals` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_goals_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_goals_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_id` INT UNSIGNED NOT NULL,
  `goal_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `url` TEXT NULL,
  `description` TEXT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fgl_lang_id_idx` (`lang_id` ASC),
  INDEX `fgl_goal_id_idx` (`goal_id` ASC),
  CONSTRAINT `fgl_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fgl_goal_id`
    FOREIGN KEY (`goal_id`)
    REFERENCES `#__fittizen_goals` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_gender`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_gender` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `location_id` INT NULL,
  `gender_id` INT NULL,
  `name` VARCHAR(50) NULL,
  `profile_code` VARCHAR(45) NULL,
  `last_name` VARCHAR(60) NULL,
  `birth_date` DATE NULL,
  `weight` FLOAT NULL,
  `height` FLOAT NULL,
  `neck` FLOAT NULL,
  `chest` FLOAT NULL,
  `hip` FLOAT NULL,
  `waist` FLOAT NULL,
  `thigh` FLOAT NULL,
  `upper_arm` FLOAT NULL,
  `block` INT NULL,
  `facebook_info` TEXT NULL,
  `created_date` DATETIME NOT NULL,
  `last_visit_date` DATETIME NOT NULL,
  `last_notification_check` DATETIME NOT NULL,
  `fb_id` INT NULL,
  `gplus_id` INT NULL,
  `twitter_id` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `ff_idx` (`user_id` ASC),
  INDEX `ff_gender_id_idx` (`gender_id` ASC),
  INDEX `ff_location_id_idx` (`location_id` ASC),
  CONSTRAINT `ff_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `#__users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ff_gender_id`
    FOREIGN KEY (`gender_id`)
    REFERENCES `#__fittizen_gender` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `ff_location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `#__fittizen_locations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_gender_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_gender_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_id` INT UNSIGNED NOT NULL,
  `gender_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fgenl_lang_id_idx` (`lang_id` ASC),
  INDEX `fgenl_gender_id_idx` (`gender_id` ASC),
  CONSTRAINT `fgenl_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fgenl_gender_id`
    FOREIGN KEY (`gender_id`)
    REFERENCES `#__fittizen_gender` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_weight_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_weight_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `weight` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fittizen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fittizen` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ffpal_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `ffpal_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_videos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_videos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `url` TEXT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL,
  `created_date` DATETIME NOT NULL,
  `video_date` DATE NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fv_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fv_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_album`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_album` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `name` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ftimeline_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `ftimeline_fitinfo_id0`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `#__fittizen_images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `url` TEXT NOT NULL,
  `url_thumb` TEXT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL,
  `created_date` DATETIME NOT NULL,
  `main` INT NULL,
  `x1` INT NULL,
  `x2` INT NULL,
  `y1` INT NULL,
  `y2` INT NULL,
  `panoramic_main` INT NULL,
  `px1` INT NULL,
  `px2` INT NULL,
  `py1` INT NULL,
  `py2` INT NULL,
  `image_date` DATE NOT NULL,
  `album_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fv_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fv_album_id_idx` (`album_id` ASC),
  CONSTRAINT `fi_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fi_album_id`
    FOREIGN KEY (`album_id`)
    REFERENCES `#__fittizen_album` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_timeline`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_timeline` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `name` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ftimeline_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `ftimeline_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_timeline_images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_timeline_images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `timeline_id` INT NOT NULL,
  `url` TEXT NOT NULL,
  `url_thumb` TEXT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL,
  `created_date` DATETIME NOT NULL,
  `main` INT NULL,
  `x1` INT NULL,
  `x2` INT NULL,
  `y1` INT NULL,
  `y2` INT NULL,
  `panoramic_main` INT NULL,
  `px1` INT NULL,
  `px2` INT NULL,
  `py1` INT NULL,
  `py2` INT NULL,
  `image_date` DATE NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fi_timeline_id_idx` (`timeline_id` ASC),
  CONSTRAINT `fi_timeline_id`
    FOREIGN KEY (`timeline_id`)
    REFERENCES `#__fittizen_timeline` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_gym`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_gym` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `gym_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `figym_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `figym_gym_id_idx` (`gym_id` ASC),
  CONSTRAINT `figym_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `figym_gym_id`
    FOREIGN KEY (`gym_id`)
    REFERENCES `#__fittizen_gyms` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_friends`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_friends` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `friend_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `block` INT NULL,
  `accepted` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `ffitfriend_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitfriend_friend_id_idx` (`friend_id` ASC),
  CONSTRAINT `ffitfriend_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitfriend_friend_id`
    FOREIGN KEY (`friend_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_permissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_permissions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `public` INT NULL,
  `age` INT NULL,
  `address` INT NULL,
  `email` INT NULL,
  `weight_history` INT NULL,
  `height_history` INT NULL,
  `waist_history` INT NULL,
  `hip_history` INT NULL,
  `neck_history` INT NULL,
  `chest_history` INT NULL,
  `upper_arm_history` INT NULL,
  `thigh_history` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitperm_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fitperm_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `#__fittizen_messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `receiver_id` INT NOT NULL,
  `sender_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fmessag_receiver_id_idx` (`receiver_id` ASC),
  INDEX `fmessag_sender_id_idx` (`sender_id` ASC),
  CONSTRAINT `fmessag_receiver_id`
    FOREIGN KEY (`receiver_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fmessag_sender_id`
    FOREIGN KEY (`sender_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fpost_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fpost_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_trainers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_trainers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ftrai_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `ftrai_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fittizen_trainers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fittizen_trainers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fittizen_id` INT NOT NULL,
  `trainer_id` INT NOT NULL,
  `accepted` INT NULL,
  `rate` INT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ftripal_trainer_id_idx` (`trainer_id` ASC),
  INDEX `ftripal_fitpal_id_idx` (`fittizen_id` ASC),
  CONSTRAINT `ftripal_fitpal_id`
    FOREIGN KEY (`fittizen_id`)
    REFERENCES `#__fittizen_fittizen` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ftripal_trainer_id`
    FOREIGN KEY (`trainer_id`)
    REFERENCES `#__fittizen_trainers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_nichos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_nichos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `nicho_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitnicho_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitnicho_nicho_id_idx` (`nicho_id` ASC),
  CONSTRAINT `fitnicho_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitnicho_nicho_id`
    FOREIGN KEY (`nicho_id`)
    REFERENCES `#__fittizen_nichos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_diet`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_diet` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `diet_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitnicho_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitnicho_diet_id_idx` (`diet_id` ASC),
  CONSTRAINT `fitdiet_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitdiet_diet_id`
    FOREIGN KEY (`diet_id`)
    REFERENCES `#__fittizen_diets` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_supplement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_supplement` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `supplement_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitnicho_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitsupplement_supplement_id_idx` (`supplement_id` ASC),
  CONSTRAINT `fitsupplement_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitsupplement_supplement_id`
    FOREIGN KEY (`supplement_id`)
    REFERENCES `#__fittizen_supplements` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_goal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_goal` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `goal_id` INT NOT NULL,
  `achieved` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitnicho_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitgoal_goal_id_idx` (`goal_id` ASC),
  CONSTRAINT `fitgoal_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitgoal_goal_id`
    FOREIGN KEY (`goal_id`)
    REFERENCES `#__fittizen_goals` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitcommentpost_fitinfo_idx` (`fitinfo_id` ASC),
  INDEX `fitcommentpost_post_id_idx` (`post_id` ASC),
  CONSTRAINT `fitcommentpost_fitinfo`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitcommentpost_post_id`
    FOREIGN KEY (`post_id`)
    REFERENCES `#__fittizen_post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitcommentimage_fintinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitcommentimage_image_id_idx` (`image_id` ASC),
  CONSTRAINT `fitcommentimage_fintinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitcommentimage_image_id`
    FOREIGN KEY (`image_id`)
    REFERENCES `#__fittizen_images` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_timeline_image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_timeline_image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `timeline_image_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitcomment_timeline_image_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitcomment_timeline_image_timeline_image_idx` (`timeline_image_id` ASC),
  CONSTRAINT `fitcomment_timeline_image_timeline_image`
    FOREIGN KEY (`timeline_image_id`)
    REFERENCES `#__fittizen_timeline_images` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitcomment_timeline_image_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_video`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_video` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `video_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitcommentvideo_video_id_idx` (`video_id` ASC),
  INDEX `fitcommentvideo_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fitcommentvideo_video_id`
    FOREIGN KEY (`video_id`)
    REFERENCES `#__fittizen_videos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitcommentvideo_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `receiver_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fpost_fitinfo_id_idx` (`sender_id` ASC),
  INDEX `ffitpost_fitinfo_id_idx` (`receiver_id` ASC),
  CONSTRAINT `ffitpost_fitinfo_id`
    FOREIGN KEY (`sender_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ffitpost_fitinfo_id2`
    FOREIGN KEY (`receiver_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_fitinfo_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_fitinfo_post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_post_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitcomment_fitinfo_post_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitcomment_fitinfopost_fitinfo_post_id_idx` (`fitinfo_post_id` ASC),
  CONSTRAINT `fitcomment_fitinfo_post_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitcomment_fitinfopost_fitinfo_post_id`
    FOREIGN KEY (`fitinfo_post_id`)
    REFERENCES `#__fittizen_fitinfo_post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NULL,
  `location_id` INT NULL,
  `name` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `init_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `feven_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `feven_location_id_idx` (`location_id` ASC),
  CONSTRAINT `feven_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `feven_location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `#__fittizen_locations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_events_attendance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_events_attendance` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `invite_date` DATETIME NOT NULL,
  `going` INT NULL,
  `response_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fevenatt_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fevenatt_event_id_idx` (`event_id` ASC),
  CONSTRAINT `fevenatt_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fevenatt_event_id`
    FOREIGN KEY (`event_id`)
    REFERENCES `#__fittizen_events` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_endurance_exercise`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_endurance_exercise` (
  `id` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_endurance_exercise_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_endurance_exercise_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `endurance_exercise_id` INT NOT NULL,
  `lang_id` INT UNSIGNED NOT NULL,
  `name` TEXT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `feel_endurance_exercise_id_idx` (`endurance_exercise_id` ASC),
  INDEX `feel_lang_id_idx` (`lang_id` ASC),
  CONSTRAINT `feel_endurance_exercise_id`
    FOREIGN KEY (`endurance_exercise_id`)
    REFERENCES `#__fittizen_endurance_exercise` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `feel_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_endurance_exercise`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_endurance_exercise` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `endurance_exercise_id` INT NOT NULL,
  `distance` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ffee_endurance_exercise_id_idx` (`endurance_exercise_id` ASC),
  INDEX `ffee_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `ffee_endurance_exercise_id`
    FOREIGN KEY (`endurance_exercise_id`)
    REFERENCES `#__fittizen_endurance_exercise` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `ffee_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fittizen_trainers_nichos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fittizen_trainers_nichos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `#__fittizen_trainers_id` INT NOT NULL,
  `nicho_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fftn_nicho_id_idx` (`nicho_id` ASC),
  INDEX `fftn_fittizen_trainers_id_idx` (`#__fittizen_trainers_id` ASC),
  CONSTRAINT `fftn_nicho_id`
    FOREIGN KEY (`nicho_id`)
    REFERENCES `#__fittizen_nichos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fftn_fittizen_trainers_id`
    FOREIGN KEY (`#__fittizen_trainers_id`)
    REFERENCES `#__fittizen_fittizen_trainers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_neck_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_neck_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `neck` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo0`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_height_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_height_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `height` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo00`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_hip_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_hip_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `hip` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo01`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_waist_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_waist_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `waist` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo010`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_chest_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_chest_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `chest` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo011`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_thigh_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_thigh_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `thigh` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo012`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_upper_arm_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_upper_arm_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `upper_arm` FLOAT NOT NULL,
  `created_date` DATETIME NOT NULL,
  `fitinfo_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fwh_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fwh_fitinfo013`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_routine`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_routine` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `trainer_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `frou_trainer_id_idx` (`trainer_id` ASC),
  CONSTRAINT `frou_trainer_id`
    FOREIGN KEY (`trainer_id`)
    REFERENCES `#__fittizen_trainers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_routine_lang`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_routine_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_id` INT UNSIGNED NOT NULL,
  `routine_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `url` TEXT NULL,
  `description` TEXT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fgl_lang_id_idx` (`lang_id` ASC),
  INDEX `fgl_goal_id_idx` (`routine_id` ASC),
  CONSTRAINT `froul_lang_id`
    FOREIGN KEY (`lang_id`)
    REFERENCES `#__languages` (`lang_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `froul_routine_id`
    FOREIGN KEY (`routine_id`)
    REFERENCES `#__fittizen_routine` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_routine`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_routine` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `routine_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `trainer_id` INT NULL,
  `active` INT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitrou_routine_id_idx` (`routine_id` ASC),
  INDEX `fitrou_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitrou_trainer_id_idx` (`trainer_id` ASC),
  CONSTRAINT `fitrou_routine_id`
    FOREIGN KEY (`routine_id`)
    REFERENCES `#__fittizen_routine` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitrou_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitrou_trainer_id`
    FOREIGN KEY (`trainer_id`)
    REFERENCES `#__fittizen_trainers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_routine_days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_routine_days` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_routine_id` INT NOT NULL,
  `day` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `frday_fitinfo_routine_id_idx` (`fitinfo_routine_id` ASC),
  CONSTRAINT `frday_fitinfo_routine_id`
    FOREIGN KEY (`fitinfo_routine_id`)
    REFERENCES `#__fittizen_fitinfo_routine` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_routine_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_routine_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_routine_days_id` INT NOT NULL,
  `achieved` INT NULL,
  `trainer_id` INT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `firouhis_fitinfo_routine_days_id_idx` (`fitinfo_routine_days_id` ASC),
  INDEX `firouhis_trainer_id_idx` (`trainer_id` ASC),
  CONSTRAINT `firouhis_fitinfo_routine_days_id`
    FOREIGN KEY (`fitinfo_routine_days_id`)
    REFERENCES `#__fittizen_fitinfo_routine_days` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `firouhis_trainer_id`
    FOREIGN KEY (`trainer_id`)
    REFERENCES `#__fittizen_trainers` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_visit_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_visit_history` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_id` INT NOT NULL,
  `visit_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitfitvihis_fitinfo_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fitfitvihis_fitinfo`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_images_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_images_tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitimg_tag_image_id_idx` (`image_id` ASC),
  INDEX `fitimg_tag_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fitimg_tag_image_id`
    FOREIGN KEY (`image_id`)
    REFERENCES `#__fittizen_images` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitimg_tag_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_videos_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_videos_tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `video_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitimg_tag_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitvid_tag_video_id_idx` (`video_id` ASC),
  CONSTRAINT `fitvid_tag_video_id`
    FOREIGN KEY (`video_id`)
    REFERENCES `#__fittizen_videos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitvid_tag_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_timeline_images_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_timeline_images_tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `timeline_image_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitimg_tag_timeline_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitimg_tag_image_id0_idx` (`timeline_image_id` ASC),
  CONSTRAINT `fitimg_tag_image_id0`
    FOREIGN KEY (`timeline_image_id`)
    REFERENCES `#__fittizen_timeline_images` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitimg_tag_fitinfo_id0`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_posts_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_posts_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_id_idx` (`post_id` ASC),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  CONSTRAINT `fitmention_id`
    FOREIGN KEY (`post_id`)
    REFERENCES `#__fittizen_post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_fitinfo_posts_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_fitinfo_posts_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fitinfo_post_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitmention_id0_idx` (`fitinfo_post_id` ASC),
  CONSTRAINT `fitmention_id0`
    FOREIGN KEY (`fitinfo_post_id`)
    REFERENCES `#__fittizen_fitinfo_post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id0`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_posts_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_posts_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_post_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitmention_id1_idx` (`comment_post_id` ASC),
  CONSTRAINT `fitmention_id1`
    FOREIGN KEY (`comment_post_id`)
    REFERENCES `#__fittizen_comment_post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id1`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_fitinfo_posts_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_fitinfo_posts_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_fitinfo_post_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitmention_id10_idx` (`comment_fitinfo_post_id` ASC),
  CONSTRAINT `fitmention_id10`
    FOREIGN KEY (`comment_fitinfo_post_id`)
    REFERENCES `#__fittizen_comment_fitinfo_post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id10`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_videos_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_videos_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_video_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitmention_id2_idx` (`comment_video_id` ASC),
  CONSTRAINT `fitmention_id2`
    FOREIGN KEY (`comment_video_id`)
    REFERENCES `#__fittizen_comment_video` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id2`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_images_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_images_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_image_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitmention_id20_idx` (`comment_image_id` ASC),
  CONSTRAINT `fitmention_id20`
    FOREIGN KEY (`comment_image_id`)
    REFERENCES `#__fittizen_comment_image` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id20`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#__fittizen_comment_timeline_images_mentions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__fittizen_comment_timeline_images_mentions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_timeline_image_id` INT NOT NULL,
  `fitinfo_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fitmention_fitinfo_id_idx` (`fitinfo_id` ASC),
  INDEX `fitmention_id200_idx` (`comment_timeline_image_id` ASC),
  CONSTRAINT `fitmention_id200`
    FOREIGN KEY (`comment_timeline_image_id`)
    REFERENCES `#__fittizen_comment_timeline_image` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fitmention_fitinfo_id200`
    FOREIGN KEY (`fitinfo_id`)
    REFERENCES `#__fittizen_fitinfos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `#__fittizen_banner_locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `location_id` INT NOT NULL,
  `banner_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fit_banlocation_location_id_idx` (`location_id` ASC),
  INDEX `fit_banlocation_banner_id_idx` (`banner_id` ASC),
  CONSTRAINT `fit_banlocation_location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `#__fittizen_locations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fit_banlocation_banner_id`
    FOREIGN KEY (`banner_id`)
    REFERENCES `#__banners` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `#__fittizen_banner_nichos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nicho_id` INT NOT NULL,
  `banner_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fit_bannicho_nicho_id_idx` (`nicho_id` ASC),
  INDEX `fit_bannicho_banner_id_idx` (`banner_id` ASC),
  CONSTRAINT `fit_bannicho_nicho_id`
    FOREIGN KEY (`nicho_id`)
    REFERENCES `#__fittizen_nichos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fit_bannicho_banner_id`
    FOREIGN KEY (`banner_id`)
    REFERENCES `#__banners` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `#__fittizen_banner_filter` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `banner_id` INT NOT NULL,
  `gender_id` INT NOT NULL,
  `min_age` INT NULL,
  `max_age` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fit_banfilter_gender_id_idx` (`gender_id` ASC),
  INDEX `fit_banfilter_banner_id_idx` (`banner_id` ASC),
  CONSTRAINT `fit_banfilter_gender_id`
    FOREIGN KEY (`gender_id`)
    REFERENCES `#__fittizen_gender` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fit_banfilter_banner_id`
    FOREIGN KEY (`banner_id`)
    REFERENCES `#__banners` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `#__fittizen_content_nichos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nicho_id` INT NOT NULL,
  `content_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fit_connicho_nicho_id_idx` (`nicho_id` ASC),
  INDEX `fit_connicho_content_id_idx` (`content_id` ASC),
  CONSTRAINT `fit_connicho_nicho_id`
    FOREIGN KEY (`nicho_id`)
    REFERENCES `#__fittizen_nichos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
CONSTRAINT `fit_connichos_content_id`
    FOREIGN KEY (`content_id`)
    REFERENCES `#__content` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
