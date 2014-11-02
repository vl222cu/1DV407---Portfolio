﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BlackJack.controller
{
    class PlayGame : model.GameObserver
    {
        public view.IView m_view;

        public bool Play(model.Game a_game, view.IView a_view)
        {
            this.m_view = a_view;
                
            this.m_view.DisplayWelcomeMessage();
            
            this.m_view.DisplayDealerHand(a_game.GetDealerHand(), a_game.GetDealerScore());
            this.m_view.DisplayPlayerHand(a_game.GetPlayerHand(), a_game.GetPlayerScore());

            if (a_game.IsGameOver())
            {
                this.m_view.DisplayGameOver(a_game.IsDealerWinner());
            }

            int input = this.m_view.GetInput();

            if (input == BlackJack.view.SwedishView.Play)
            {
                a_game.NewGame();
            }
            else if (input == BlackJack.view.SwedishView.Hit)
            {
                a_game.Hit();
            }
            else if (input == BlackJack.view.SwedishView.Stand)
            {
                a_game.Stand();
            }

            return input != BlackJack.view.SwedishView.Quit;
        }

        public void CardDealt()
        {
            this.m_view.ThreadSleep2000();
        }
    }
}
