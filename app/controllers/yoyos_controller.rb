class YoyosController < ApplicationController

  def new
    @yoyo = Yoyo.new
  end

  def create
    if current_user.yoyos.create(params[:yoyo])
      flash[:msg] = "Yoyo added to collection successfully"
      redirect_to yoyos_url
    else
      render :action => "new"
    end
  end

  def show
    @yoyo = Yoyo.find(params[:id])
  end

end
