using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.model
{
    class Game
    {
        private model.Dealer m_dealer;
        private model.Player m_player;

        private const int SWEDISH_NEW_GAME_STRATEGY = 1;
        private const int AMERICAN_NEW_GAME_STRATEGY = 2;
        private const int INTERNATIONAL_NEW_GAME_STRATEGY = 3;

        public Game()
        {
            m_dealer = new Dealer(new rules.RulesFactory(SWEDISH_NEW_GAME_STRATEGY));
            m_player = new Player();
        }

        public void setPlayerListener(controller.PlayGame c)
        {
            m_player.RegisterListener(c);
        }

        public bool IsGameOver()
        {
            return m_dealer.IsGameOver();
        }

        public bool IsDealerWinner()
        {
            return m_dealer.IsDealerWinner(m_player);
        }

        public bool NewGame()
        {
            return m_dealer.NewGame(m_player);
        }

        public bool Hit()
        {
            return m_dealer.Hit(m_player);
        }

        public bool Stand()
        {
            m_dealer.Stand();
            return true;
        }

        public IEnumerable<Card> GetDealerHand()
        {
            return m_dealer.GetHand();
        }

        public IEnumerable<Card> GetPlayerHand()
        {
            return m_player.GetHand();
        }

        public int GetDealerScore()
        {
            return m_dealer.CalcScore();
        }

        public int GetPlayerScore()
        {
            return m_player.CalcScore();
        }
    }
}
