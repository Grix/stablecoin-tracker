CREATE TABLE stablecoin_info (  id				INTEGER			NOT NULL		AUTO_INCREMENT, 
                                ticker          VARCHAR(255)    NOT NULL, 
                                name            VARCHAR(255)	NOT NULL, 
                                mcap    		FLOAT			NOT NULL,
                                volume          FLOAT
                                cmc_link        VARCHAR(2083),
                                peg             BOOLEAN,
                                decentralized   BOOLEAN,
                                stable_now      BOOLEAN,
                                stable_past     BOOLEAN,
                                platform        VARCHAR(255),
                                PRIMARY KEY (id)) ENGINE=InnoDB;