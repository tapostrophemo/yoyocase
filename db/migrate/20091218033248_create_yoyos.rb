class CreateYoyos < ActiveRecord::Migration
  def self.up
    create_table :yoyos do |t|
      t.string :manufacturer
      t.string :country
      t.integer :model_year
      t.string :model_name

      t.integer :user_id

      t.timestamps
    end
  end

  def self.down
    drop_table :yoyos
  end
end
