/******************************************************************************

CAMPSITE is a Unicode-enabled multilingual web content
management system for news publications.
CAMPFIRE is a Unicode-enabled java-based near WYSIWYG text editor.
Copyright (C)2000,2001  Media Development Loan Fund
contact: contact@campware.org - http://www.campware.org
Campware encourages further development. Please let us know.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

******************************************************************************/

#include "articlecomment.h"
#include "util.h"

CArticleComment::CArticleComment(id_type p_nMessageId)
	: m_bExists(false), m_nArticleNumber(-1), m_nLanguageId(-1), m_nMessageId(-1),
	m_nForumId(-1), m_nThreadId(-1), m_nParentId(-1), m_nStatus(-1), m_nUserId(-1),
	m_nThreadCount(0), m_nDateStamp(0), m_nViewcount(0), m_nClosed(-1), m_nLevel(-1)
{
	stringstream buf;
	buf << "select pm.forum_id, pm.thread, pm.parent_id, pm.author, pm.subject, pm.body,"
			<< " pm.email, pm.ip, pm.status, pm.modifystamp, pm.user_id, pm.thread_count,"
			<< " pm.datestamp, pm.viewcount, pm.closed, ac.fk_article_number, ac.fk_language_id "
			<< "from phorum_messages as pm left join ArticleComments as ac"
			<< " on pm.message_id = ac.fk_comment_thread_id "
			<< "where pm.message_id = '" << p_nMessageId << "'";
	CMYSQL_RES coQRes;
	MYSQL_ROW row = QueryFetchRow(MYSQLConnection(), buf.str(), coQRes);
	if (row == NULL || row[0] == NULL)
	{
		return;
	}
	m_bExists = true;
	m_nMessageId = p_nMessageId;
	m_nForumId = strtol(row[0], 0, 10);
	m_nThreadId = strtol(row[1], 0, 10);
	m_nParentId = strtol(row[2], 0, 10);
	m_coAuthor = row[3];
	m_coSubject = row[4];
	m_coBody = row[5];
	m_coEmail = row[6];
	m_coIP = row[7];
	m_nStatus = strtol(row[8], 0, 10);
	m_nModifyStamp = strtol(row[9], 0, 10);
	m_nUserId = strtol(row[10], 0, 10);
	m_nThreadCount = strtol(row[11], 0, 10);
	m_nDateStamp = strtol(row[12], 0, 10);
	m_nViewcount = strtol(row[13], 0, 10);
	m_nClosed = strtol(row[14], 0, 10);
	m_nArticleNumber = strtol(row[15], 0, 10);
	m_nLanguageId = strtol(row[16], 0, 10);
}


CArticleComment::~CArticleComment()
{
}

bool CArticleComment::createComment(String2String& p_rcoValues, bool& p_rbRejected)
{
	p_rbRejected = !CArticleComment::CommentAccepted(p_rcoValues);
	return false;
}

bool CArticleComment::deleteComment()
{
	return false;
}

bool CArticleComment::incrementViewCount()
{
	if (!m_bExists)
	{
		return false;
	}
	stringstream buf;
	buf << "update phorum_messages set viewcount = viewcount + 1 where message_id = '"
			<< m_nMessageId << "'";
	return mysql_query(MYSQLConnection(), buf.str().c_str()) == 0;
}

bool CArticleComment::setClosed(bool p_bClosed)
{
	if (!m_bExists)
	{
		return false;
	}
	stringstream buf;
	buf << "update phorum_messages set closed = '" << (int)p_bClosed
			<< "' where message_id = '" << m_nMessageId << "'";
	return mysql_query(MYSQLConnection(), buf.str().c_str()) == 0;
}

bool CArticleComment::operator == (const CArticleComment& p_rcoSource) const
{
	return m_bExists == p_rcoSource.m_bExists
			&& m_nArticleNumber == p_rcoSource.m_nArticleNumber
			&& m_nLanguageId == p_rcoSource.m_nLanguageId
			&& m_nMessageId == p_rcoSource.m_nMessageId
			&& m_nForumId == p_rcoSource.m_nForumId
			&& m_nThreadId == p_rcoSource.m_nThreadId
			&& m_nParentId == p_rcoSource.m_nParentId
			&& m_coAuthor == p_rcoSource.m_coAuthor
			&& m_coSubject == p_rcoSource.m_coSubject
			&& m_coBody == p_rcoSource.m_coBody
			&& m_coEmail == p_rcoSource.m_coEmail
			&& m_coIP == p_rcoSource.m_coIP
			&& m_nStatus == p_rcoSource.m_nStatus
			&& m_nModifyStamp == p_rcoSource.m_nModifyStamp
			&& m_nUserId == p_rcoSource.m_nUserId
			&& m_nThreadCount == p_rcoSource.m_nThreadCount
			&& m_nDateStamp == p_rcoSource.m_nDateStamp
			&& m_nViewcount == p_rcoSource.m_nViewcount
			&& m_nClosed == p_rcoSource.m_nClosed
			&& m_nLevel == p_rcoSource.m_nLevel;
}

bool CArticleComment::IsUserBlocked(id_type p_nUserId)
{
	return false;
}

bool CArticleComment::Moderated(id_type p_nPublicationId)
{
	return false;
}

ulint CArticleComment::ArticleCommentCount(id_type p_nArticleNumber, id_type p_nLanguageId)
{
	stringstream buf;
	buf << "select count(*) from ArticleComments where fk_article_number = '"
			<< p_nArticleNumber << "' and fk_language_id = '" << p_nLanguageId << "'";
	CMYSQL_RES coQRes;
	MYSQL_ROW row = QueryFetchRow(MYSQLConnection(), buf.str(), coQRes);
	if (row == NULL || row[0] == NULL)
	{
		return 0;
	}
	return strtol(row[0], 0, 10);
}

bool CArticleComment::ArticleCommentsEnabled(id_type p_nPublicationId,
											 id_type p_nArticleNumber,
											 id_type p_nLanguageId)
{
	stringstream buf;
	buf << "select comments_enabled from Publications where Id = '" << p_nPublicationId << "'";
	CMYSQL_RES coQRes;
	MYSQL_ROW row = QueryFetchRow(MYSQLConnection(), buf.str(), coQRes);
	if (row == NULL || row[0] == NULL || strtol(row[0], 0, 10) == 0)
	{
		return false;
	}

	buf.str("");
	buf << "select comments_enabled, Type from Articles where Number = '" << p_nArticleNumber
			<< "' and IdLanguage = '" << p_nLanguageId << "'";
	row = QueryFetchRow(MYSQLConnection(), buf.str(), coQRes);
	if (row == NULL || row[0] == NULL || strtol(row[0], 0, 10) == 0)
	{
		return false;
	}

	buf.str("");
	buf << "select comments_enabled from ArticleTypeMetadata where type_name = '"
			<< row[1] << "' and field_name = 'NULL'";
	row = QueryFetchRow(MYSQLConnection(), buf.str(), coQRes);
	if (row == NULL || row[0] == NULL || strtol(row[0], 0, 10) == 0)
	{
		return false;
	}
	return true;
}

bool CArticleComment::CommentAccepted(String2String& p_rcoValues) const
{
	return false;
}
