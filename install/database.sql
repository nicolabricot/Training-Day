-- Utilisateurs

CREATE TABLE IF NOT EXISTS user_data (
    id              int                     NOT NULL                AUTO_INCREMENT,
    login           varchar(50)             NOT NULL,
    password        varchar(64)             NOT NULL,
    name            varchar(50)             NOT NULL,
    surname         varchar(50)             NOT NULL,
    mail            varchar(320),
    PRIMARY KEY (id),
    UNIQUE KEY (login)
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS user_permissions (
    id              int                     NOT NULL                AUTO_INCREMENT,
    name            varchar(50)             NOT NULL,
    permissions     text,
    PRIMARY KEY (id),
    UNIQUE KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS user_in (
    user            int                     NOT NULL,
    permissions     int                     NOT NULL,
    PRIMARY KEY (user, permissions),
    INDEX (user),
    FOREIGN KEY (user) 
        REFERENCES user_data (id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (permissions),
    FOREIGN KEY (permissions) 
        REFERENCES user_permissions (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET = utf8;

-- Computer

CREATE TABLE IF NOT EXISTS computer (
    id              int                     NOT NULL                AUTO_INCREMENT,
    type            varchar(320)            NOT NULL,
    cpu             varchar(320)            NOT NULL,
    gpu             varchar(320)            NOT NULL,
    ram             varchar(320)            NOT NULL,
    screen          varchar(320)            NOT NULL,
    drive           varchar(320)            NOT NULL,
    optique         varchar(320)            NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS computer_user (
    computer        int                     NOT NULL                AUTO_INCREMENT,
    user            int                     NOT NULL,
    PRIMARY KEY (computer),
    INDEX (user),
    FOREIGN KEY (user) 
        REFERENCES user_data (id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (computer),
    FOREIGN KEY (computer) 
        REFERENCES computer (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

-- Jeux

CREATE TABLE IF NOT EXISTS game (
    id              int                     NOT NULL                AUTO_INCREMENT,
    name            varchar(50)             NOT NULL,
    description     text,
    cover           varchar(320)            NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (login)
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

-- Tournois

CREATE TABLE IF NOT EXISTS team (
    id              int                     NOT NULL                AUTO_INCREMENT,
    name            varchar(50)             NOT NULL,
    description     text,
    PRIMARY KEY (id),
    UNIQUE KEY (login)
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS team_player (
    team            int                     NOT NULL,
    player          int                     NOT NULL,
    PRIMARY KEY (team, player),
    INDEX (player),
    FOREIGN KEY (player) 
        REFERENCES user_data (id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (team),
    FOREIGN KEY (team) 
        REFERENCES team (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS tournament (
    id              int                     NOT NULL                AUTO_INCREMENT,
    name            varchar(50)             NOT NULL,
    description     text,
    PRIMARY KEY (id),
    UNIQUE KEY (login)
) ENGINE=InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS tournament_game (
    game            int                     NOT NULL,
    tournament      int                     NOT NULL,
    PRIMARY KEY (team, tournament),
    INDEX (tournament),
    FOREIGN KEY (tournament) 
        REFERENCES tournament (id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (game),
    FOREIGN KEY (game) 
        REFERENCES game (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS team_inscription (
    team            int                     NOT NULL,
    tournament      int                     NOT NULL,
    PRIMARY KEY (team, tournament),
    INDEX (tournament),
    FOREIGN KEY (tournament) 
        REFERENCES tournament (id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (team),
    FOREIGN KEY (team) 
        REFERENCES team (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS schedule (
    tournament       int                    NOT NULL,
    start            int                    NOT NULL,
    stop             int                    NOT NULL,
    PRIMARY KEY (tournament, start, stop),
    INDEX (tournament),
    FOREIGN KEY (tournament) 
        REFERENCES tournament (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET = utf8;