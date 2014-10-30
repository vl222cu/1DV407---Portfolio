using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model
{
    class Dealer : Player
    {
        private Deck m_deck = null;
        private const int g_maxScore = 21;

        private rules.INewGameStrategy m_newGameRule;
        private rules.IHitStrategy m_hitRule;

        public rules.UniqueRuleset m_ruleset;

        public void Deal(Player a_player, bool result)
        {
            Card c;
            c = m_deck.GetCard();
            c.Show(result);
            a_player.DealCard(c);
        }

        public Dealer(rules.RulesFactory a_rulesFactory)
        {
            m_newGameRule = a_rulesFactory.GetNewGameRule();
            m_hitRule = a_rulesFactory.GetHitRule();
        }

        public bool NewGame(Player a_player)
        {
            if (m_deck == null || IsGameOver())
            {
                m_deck = new Deck();
                ClearHand();
                a_player.ClearHand();
                m_ruleset = new rules.UniqueRuleset(a_player, this, g_maxScore);
                return m_newGameRule.NewGame(m_deck, this, a_player);   
            }
            return false;
        }

        public bool Hit(Player a_player)
        {
            if (m_deck != null && a_player.CalcScore() < g_maxScore && !IsGameOver())
            {
                Deal(a_player, true);

                return true;
            }
            return false;
        }

        public bool IsDealerWinner(Player a_player)
        {
            return this.m_ruleset.isDealerWinner(a_player);
        }

        public bool IsGameOver()
        {
            if (m_deck != null && /*CalcScore() >= g_hitLimit*/ m_hitRule.DoHit(this) != true)
            {
                return true;
            }
            return false;
        }

        public void Stand()
        {
            ShowHand();

            while(m_hitRule.DoHit(this))
            {
                Deal(this, true);
            }
        }

    }
}
