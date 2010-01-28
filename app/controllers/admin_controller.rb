class AdminController < ApplicationController
  def index
  end

  def show_accounts
    @users = User.find(:all)
  end
end
