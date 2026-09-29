CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    CONSTRAINT uq_categories_slug UNIQUE (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_path VARCHAR(2048) NOT NULL,
    preview_path VARCHAR(2048) NOT NULL,
    alt VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    views INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NOT NULL,
    CONSTRAINT uq_posts_slug UNIQUE (slug),
    CONSTRAINT fk_posts_image
        FOREIGN KEY (image_id) REFERENCES images (id)
        ON DELETE RESTRICT,
    INDEX idx_posts_published_at (published_at),
    INDEX idx_posts_views (views)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE category_post (
    category_id BIGINT UNSIGNED NOT NULL,
    post_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (category_id, post_id),
    CONSTRAINT fk_category_post_category
        FOREIGN KEY (category_id) REFERENCES categories (id)
        ON DELETE CASCADE,
    CONSTRAINT fk_category_post_post
        FOREIGN KEY (post_id) REFERENCES posts (id)
        ON DELETE CASCADE,
    INDEX idx_category_post_post_id (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
