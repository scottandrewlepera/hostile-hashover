<?php namespace HashOver;

// Copyright (C) 2025 Scott Andrew LePera
// This file is part of a HashOver extension.
//
// HashOver is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// HashOver is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with HashOver.  If not, see <http://www.gnu.org/licenses/>.

class RSSOverview extends Database {

	public function __construct (Setup $setup)
	{
		// Construct parent class
		parent::__construct ($setup);
	}

  public function getRSSOverview () {

		$statement = <<< END
		SELECT comments.*,
		"page-info".title as page_title,
		"page-info".url as page_url
		FROM comments
		JOIN "page-info" on "page-info".thread = comments.thread
		ORDER BY date DESC
		LIMIT 15
		END;

		$results = $this->executeStatement ($statement);

		if ($results !== false) {
			return $results->fetchAll (\PDO::FETCH_ASSOC);
		}
		return array ();
	}

	public function checkRSSToken (String $token) {
		if (!isset($this->setup->rssToken) || 
				$this->setup->rssToken === '' ||
				$token !== $this->setup->rssToken) {
				return false;
		}
		return true;
	}
}